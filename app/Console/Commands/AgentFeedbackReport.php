<?php

namespace App\Console\Commands;

use App\Audit;
use App\Mail\Agentfeedback;
use App\Partner;
use App\RawData;
use App\Rebuttal;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;

class AgentFeedbackReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Feedback:Report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Rows fetched per chunk. Keeps memory bounded on large date ranges and lets us
     * batch the Rebuttal lookup per chunk instead of running it once per row.
     */
    private $chunkSize = 500;

    /**
     * Keywords used to find the right dynamic field when raw_data doesn't carry
     * agent_name / emp_id / lob directly (this is the case for RCC sheets, where those
     * values live in audit_field_values against a dynamic AuditFormFields entry).
     * Matching is done against a NORMALIZED field_name (lowercased, punctuation stripped,
     * whitespace collapsed) so naming drift like "Agent  Name", "BPA Name:", "EMP Id" etc.
     * all resolve correctly without needing an ever-growing keyword list.
     */
    private $agentNameKeywords = ['agent name', 'advisor name', 'advisior name', 'associate name', 'bpa name', 'emp name', 'auditee name'];
    private $empIdKeywords     = ['emp id', 'employee id', 'emp code', 'bpa id', 'auditee id'];
    private $lobKeywords       = ['lob'];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $now = Carbon::now();
        $today = $now->copy();
        // $today = $now->subday();

        if ($today->day === 1) {
            if ($today->isWeekend()) {
                $startOfMonth = $today->copy()->startOfMonth()->subDays($today->dayOfWeek === Carbon::SUNDAY ? 2 : 1);
            } else {
                $startOfMonth = $today->copy()->startOfMonth();
            }
        } else {
            // if($today->isWeekend()) {
            //     $today->subDays($today->dayOfWeek === Carbon::SATURDAY ? 1 : 2);
            // }
            $startOfMonth = $today->copy()->startOfMonth();
            if ($today->day < 4) {
                $startOfMonth->subMonth();
            }
        }

        // --- Header (built once) ---
        $header = "<table style='border:1px solid black;'>
      <tr>
      <th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> No.</th>";
        $header .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> Audit Date</th>";
        $header .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> Call ID</th>";
        $header .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> Partner </th>";
        $header .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> Process</th>";
        $header .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> Location</th>";
        $header .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> LOB</th>";
        $header .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> feedback_shared_status</th>";
        $header .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> feedback ViEW DATE</th>";
        $header .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> feedback_shared_date</th>";
        $header .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> feedback_remarks</th>";
        $header .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> Agent name</th>";
        $header .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> Employee ID</th>";
        $header .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> Quality Score</th>";
        $header .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> TAT(days)</th>";
        $header .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> Within TAT</th>";
        $header .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> Fatal Error Count</th>";
        $header .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> Fatal Error %</th>";
        $header .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> Non-Fatal Error Count</th>";
        $header .= "<th style='border:1px solid black;text-align: center;background-color: #FFE4C4;'> Non-Fatal Error %</th>";

        // Row html accumulates in an array and gets imploded once at the end -
        // much cheaper than repeated string concatenation over thousands of rows.
        $rowsHtml = [];
        $number = 1;

        $query = Audit::whereHas('raw_data', function (Builder $query) {
            $query->where('client_id', 23);
        })
            ->whereDate('audit_date', '>=', $startOfMonth)
            ->whereDate('audit_date', '<=', $today)
            ->where('deleted', 0)
            ->with([
                'raw_data',
                'raw_data.partner_detail',
                'raw_data.location_data',
                'raw_data.process',
                'audit_results',
                'audit_field_values',
                'audit_field_values.field',
            ])
            ->orderBy('id');

        // chunkById streams the result set in bounded batches instead of loading the
        // whole month into memory at once, and lets us batch the Rebuttal lookup per
        // batch (one query per 500 rows) instead of one query per row (N+1).
        $query->chunkById($this->chunkSize, function ($chunk) use (&$rowsHtml, &$number) {
            $rawDataIds = $chunk->pluck('raw_data.id')->filter()->unique()->values()->all();

            // Batch fetch: raw_data_id => has at least one rebuttal (bool)
            $rebuttalRawDataIds = empty($rawDataIds)
                ? []
                : Rebuttal::whereIn('raw_data_id', $rawDataIds)
                    ->distinct()
                    ->pluck('raw_data_id')
                    ->flip() // isset() lookup instead of in_array()
                    ->toArray();

            foreach ($chunk as $value) {
                $rowsHtml[] = $this->buildRowHtml($value, $number, $rebuttalRawDataIds);
                $number++;
            }
        });

        $test = $header . implode('', $rowsHtml) . "</tbody></table>";

        $file1 = "AgentfeedbackReport1.xls";
        $xls1 = new \HtmlExcel();
        $xls1->addSheet("AgentfeedbackReport", $test);
        $ddddd1 = $xls1->buildFile();
        file_put_contents(public_path($file1), $ddddd1);

        // $attachments = [
        //     [
        //         'file' => $file1,
        //         'options' => [
        //             'as' => 'AgentfeedbackReport1.xls',
        //         ],
        //     ],
        //     [
        //         'file' => $file2,
        //         'options' => [
        //             'as' => 'AgentfeedbackReport2.xls',
        //         ],
        //     ],
        // ];
        // $subject = " Monthly Feedback Report";

        // $cc_emails = ["palak.khandelwal@qdegrees.org"];
        // $to_emails = ["palak.khandelwal@qdegrees.org"];
        // Mail::to($to_emails)->cc($cc_emails)->send(new Agentfeedback($subject, $attachments));
    }

    /**
     * Build the <tr> html for a single audit row.
     * $rebuttalRawDataIds is a [raw_data_id => true] lookup batched per chunk (avoids N+1).
     */
    private function buildRowHtml($value, $number, array $rebuttalRawDataIds)
    {
        $rawData = $value->raw_data;

        $auditDate = Carbon::parse($value->audit_date);
        $feedbacksharedate = $value->feedback_shared_date ? Carbon::parse($value->feedback_shared_date) : null;
        $feedbackViewDate  = Carbon::parse($value->audit_view_date);

        $diffInHours = null;
        $diffInDays = null;
        $diff_share_date = 0;

        if ($feedbacksharedate !== null) {
            $diffInHours = $feedbacksharedate->diffInHours($feedbackViewDate);
            $diffInDays = $feedbacksharedate->diffInDays($feedbackViewDate);
            $diff_share_date = $feedbacksharedate->diffInDays($auditDate);
        }

        if ($diffInHours !== null && $diffInHours % 24 != 0) {
            $diffInDays += 1;
        }

        // Resolve agent name / emp id / lob in a single pass over audit_field_values
        // instead of three separate passes (was the case previously).
        $dynamic = $this->resolveDynamicFields(
            $rawData->agent_name ?? null,
            $rawData->emp_id ?? null,
            $value->lob ?? ($rawData->lob ?? null),
            $value->audit_field_values
        );

        $stats = $this->computeFatalStats($value->audit_results);

        $callId = "'" . $rawData->call_id;

        $row = "<tr>
            <td style='border:1px solid black;text-align: center;'>{$number}</td>";
        $row .= "<td style='border:1px solid black;text-align: center;'>$auditDate</td>";
        $row .= "<td style='border:1px solid black;text-align: center;'>{$callId}</td>";
        $row .= "<td style='border:1px solid black;text-align: center;'>" . e(optional($rawData->partner_detail)->name ?? '') . "</td>";
        $row .= "<td style='border:1px solid black;text-align: center;'>" . e(optional($rawData->process)->name ?? '') . "</td>";
        $row .= "<td style='border:1px solid black;text-align: center;'>" . e($rawData->location_data->name ?? '') . "</td>";
        $row .= "<td style='border:1px solid black;text-align: center;'>" . e($dynamic['lob'] ?? 'NA') . "</td>";

        $row .= $value->feedback_shared_status == 1
            ? "<td style='border:1px solid black;text-align: center;'>Feedback Shared</td>"
            : "<td style='border:1px solid black;text-align: center;'>Feedback Not Shared</td>";

        $row .= "<td style='border:1px solid black;text-align: center;'>" . date_format($feedbackViewDate, 'Y-m-d') . "</td>";
        $row .= "<td style='border:1px solid black;text-align: center;'>" . ($feedbacksharedate ? $feedbacksharedate->format('Y-m-d') : '') . "</td>";
        $row .= "<td style='border:1px solid black;text-align: center;'>" . e($value->feedback_comment ?? '') . "</td>";
        $row .= "<td style='border:1px solid black;text-align: center;'>" . e($dynamic['agent_name'] ?? 'NA') . "</td>";
        $row .= "<td style='border:1px solid black;text-align: center;'>" . e($dynamic['emp_id'] ?? 'NA') . "</td>";
        $row .= "<td style='border:1px solid black;text-align: center;'>" . (round($value->with_fatal_score_per)) . "%</td>";
        $row .= "<td style='border:1px solid black;text-align: center;'>$diffInDays</td>";

        $hasRebuttal = isset($rebuttalRawDataIds[$rawData->id]);

        if ($feedbacksharedate && $diffInDays !== null) {
            if ($diff_share_date == 0 || $diff_share_date == 1) {
                $row .= "<td style='border:1px solid black;text-align: center;'>Within TAT</td>";
            } elseif ($hasRebuttal) {
                $row .= "<td style='border:1px solid black;text-align: center;'>Rebuttal Affected</td>";
            } elseif ($diffInDays <= 3) {
                $row .= "<td style='border:1px solid black;text-align: center;'>Within TAT</td>";
            } else {
                $row .= "<td style='border:1px solid black;text-align: center;'>Not in TAT</td>";
            }
        } else {
            $row .= $hasRebuttal
                ? "<td style='border:1px solid black;text-align: center;'>Rebuttal Affected</td>"
                : "<td style='border:1px solid black;text-align: center;'>No feedback date</td>";
        }

        $row .= "<td style='border:1px solid black;text-align: center;'>{$stats['fatal_count']}</td>";
        $row .= "<td style='border:1px solid black;text-align: center;'>{$stats['fatal_percent']}%</td>";
        $row .= "<td style='border:1px solid black;text-align: center;'>{$stats['not_fatal_count']}</td>";
        $row .= "<td style='border:1px solid black;text-align: center;'>{$stats['not_fatal_percent']}%</td>";

        $row .= "</tr>";

        return $row;
    }

    /**
     * Resolve agent_name / emp_id / lob in ONE pass over the audit's dynamic field
     * values, instead of iterating the same collection three separate times.
     * Static (raw_data) values win when present; dynamic AuditFormFields values are
     * the fallback for RCC sheets. field_name is normalized (lowercased, punctuation
     * stripped, whitespace collapsed) before keyword matching so naming drift like
     * "Agent  Name" (double space) or "BPA Name:" (trailing colon) resolves correctly.
     */
    private function resolveDynamicFields($staticAgentName, $staticEmpId, $staticLob, $dynamicFieldValues)
    {
        $resolved = [
            'agent_name' => $staticAgentName ?: null,
            'emp_id'     => $staticEmpId ?: null,
            'lob'        => $staticLob ?: null,
        ];

        // Nothing left to resolve, or nothing to resolve it from - skip the loop entirely.
        if ((!empty($resolved['agent_name']) && !empty($resolved['emp_id']) && !empty($resolved['lob'])) || !$dynamicFieldValues) {
            return $resolved;
        }

        foreach ($dynamicFieldValues as $fv) {
            // Stop early once everything is resolved.
            if (!empty($resolved['agent_name']) && !empty($resolved['emp_id']) && !empty($resolved['lob'])) {
                break;
            }

            $rawFieldName = optional($fv->field)->field_name ?? '';
            if ($rawFieldName === '') {
                continue;
            }

            $fieldName = strtolower(trim($rawFieldName));
            $fieldName = preg_replace('/[^a-z0-9\s]/', ' ', $fieldName); // strip punctuation (colons, slashes, etc.)
            $fieldName = preg_replace('/\s+/', ' ', $fieldName);         // collapse repeated/double spaces
            $fieldName = trim($fieldName);

            if (empty($resolved['agent_name']) && $this->matchesAny($fieldName, $this->agentNameKeywords)) {
                $resolved['agent_name'] = $fv->field_value;
                continue;
            }
            if (empty($resolved['emp_id']) && $this->matchesAny($fieldName, $this->empIdKeywords)) {
                $resolved['emp_id'] = $fv->field_value;
                continue;
            }
            if (empty($resolved['lob']) && $this->matchesAny($fieldName, $this->lobKeywords)) {
                $resolved['lob'] = $fv->field_value;
                continue;
            }
        }

        return $resolved;
    }

    private function matchesAny($haystack, array $keywords)
    {
        foreach ($keywords as $keyword) {
            if (str_contains($haystack, $keyword)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Given a collection of audit_results rows, work out fatal / not-fatal counts and
     * percentages. Fatal = selected_option == 3 (same convention used in the raw dump report).
     *
     * Example: 5 sub-parameters, 2 have selected_option == 3
     *   -> fatal_count = 2, fatal_percent = 2/5*100 = 40
     *   -> not_fatal_count = 3, not_fatal_percent = 3/5*100 = 60
     */
    private function computeFatalStats($auditResults)
    {
        $total = $auditResults->count();
        $fatalCount = $auditResults->where('selected_option', 3)->count();
        $notFatalCount = $total - $fatalCount;

        $fatalPercent = $total > 0 ? round(($fatalCount / $total) * 100, 2) : 0;
        $notFatalPercent = $total > 0 ? round(($notFatalCount / $total) * 100, 2) : 0;

        return [
            'total'             => $total,
            'fatal_count'       => $fatalCount,
            'not_fatal_count'   => $notFatalCount,
            'fatal_percent'     => $fatalPercent,
            'not_fatal_percent' => $notFatalPercent,
        ];
    }
}