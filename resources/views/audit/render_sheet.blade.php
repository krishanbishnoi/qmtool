<!DOCTYPE html>

<html lang="en">

<!-- begin::Head -->

<head>
    <meta charset="utf-8" />
    <title>QM Tool</title>
    <meta name="description" content="QuoteNow">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--begin::Fonts -->
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
 <script>
     WebFont.load({
         google: {
             "families": ["Poppins:300,400,500,600,700", "Roboto:300,400,500,600,700"]
         },
         active: function() {
             sessionStorage.fonts = true;
         }
     });
 </script> -->

    <!--end::Fonts -->
    <style type="text/css">
        table tr td {
            text-align: justify;
        }
    </style>


    <!--begin::Page Vendors Styles(used by this page) -->

    {!! Html::style('assets/vendors/base/vendors.bundle.css') !!}

    <!--RTL version:{!! Html::style('assets/vendors/base/vendors.bundle.rtl.css') !!}-->
    {!! Html::style('assets/demo/default/base/style.bundle.css') !!}

    <!--RTL version:{!! Html::style('assets/demo/default/base/style.bundle.rtl.css') !!}-->

    <!--end::Global Theme Styles -->

    <!--begin::Layout Skins(used by all pages) -->
    {!! Html::style('assets/demo/default/skins/header/base/light.css') !!}

    <!--RTL version:{!! Html::style('assets/demo/default/skins/header/base/light.rtl.css') !!}-->
    {!! Html::style('assets/demo/default/skins/header/menu/light.css') !!}

    <!--RTL version:{!! Html::style('assets/demo/default/skins/header/menu/light.rtl.css') !!}-->
    {!! Html::style('assets/demo/default/skins/brand/dark.css') !!}

    <!--RTL version:{!! Html::style('assets/demo/default/skins/brand/dark.rtl.css') !!}-->
    {!! Html::style('assets/demo/default/skins/aside/dark.css') !!}

    <!--RTL version:{!! Html::style('assets/demo/default/skins/aside/dark.rtl.css') !!}-->

    <!--end::Layout Skins -->
    <link rel="shortcut icon" href="{{ url('assets/media/logos/logo_default_dark.png') }}" />
</head>

<!-- end::Head -->

<!-- begin::Body -->

<body
    class="kt-header--fixed kt-header-mobile--fixed kt-subheader--fixed kt-subheader--enabled kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading">
    <div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
        <div id="app">

            <div class="row">
                <div class="col-md-4">
                    @if ($data->client->logo)
                    <img src="{{ Storage::url('company/_' . $data->company_id . '/client/' . $data->client->logo) }}"
                        width="80">
                    @endif
                </div>
                <?php if (count($sheet_data) > 0) { ?>
                    <div class="col-md-6">
                        <?php
                        $selected_sheetid = substr($_SERVER['REQUEST_URI'], strrpos($_SERVER['REQUEST_URI'], '/') + 1);
                        //dd($selected_sheetid);
                        ?>
                        <select class="kt-font-brand kt-font-bolder"
                            style="text-align: center; line-height: 60px;height: 33px;width: 50%;"
                            onchange="redirect(this.value);">
                            <option value=''>Select Process</option>
                            @foreach ($sheet_data as $sheet)
                            <option value="{{ Crypt::encrypt($sheet->id) }}"
                                @if (Crypt::decrypt($selected_sheetid)==$sheet->id) selected="selected" @endif>
                                {{ $sheet->name . '-' . $sheet->process['name'] }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                <?php } else { ?>
                    <div class="col-md-8">
                        <h5 class="kt-font-brand kt-font-bolder" style="text-align: center; line-height: 60px;">
                            {{ $data->name }}
                        </h5>
                    </div>
                <?php } ?>
                <div class="col-md-2" style="padding-top: 17px;">
                    <img src="/img/logo.png" width="100" align="right">
                </div>
            </div>
            <br />
            <a href="/qa_dashboard/qa_dashboard_new" class="btn btn-primary"><i style="font-size: 12px;"
                    class="fa fa-arrow-left"></i>Back</a>
            <form id="logout-form" action="{{ route('logout') }}" style="float: right"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();" method="POST"
                style="display: none;">
                <button href="javascript:void(0);" type="submit" class="btn btn-danger">Logout</button>
                {{ csrf_field() }}
            </form>
            <br />
            <br />
            <!-- <audit qm-sheet-id="{{ $qm_sheet_id }}"
  today-date="{{ date('d-m-Y') }}"
  auditor-name="{{ Auth::user()->name }}"
  auditor-id="{{ Crypt::encrypt(Auth::user()->id) }}"></audit> -->
            {{-- @if (Crypt::decrypt($qm_sheet_id) == 233) --}}
            @if ($data->process_id == 138 || $data->process_id == 171)
            <bajaj-audit-component qm-sheet-id="{{ $qm_sheet_id }}" today-date="{{ date('d-m-Y') }}"
                auditor-name="{{ Auth::user()->name }}"
                auditor-id="{{ Crypt::encrypt(Auth::user()->id) }}"></bajaj-audit-component>
            @elseif($data->process_id == 145 || $data->process_id == 156)
            <audit-bajaj-cancellation-sheet qm-sheet-id="{{ $qm_sheet_id }}" today-date="{{ date('d-m-Y') }}"
                auditor-name="{{ Auth::user()->name }}" auditor-id="{{ Crypt::encrypt(Auth::user()->id) }}">
            </audit-bajaj-cancellation-sheet>
            @elseif(
            $data->process_id == 160 ||
            $data->process_id == 175 ||
            $data->process_id == 180 ||
            $data->process_id == 181 ||
            $data->process_id == 183 ||
            $data->process_id == 184 ||
            $data->process_id == 185 ||
            $data->process_id == 192 ||
            $data->process_id == 193||
            $data->process_id == 210 || $data->process_id == 211 || $data->process_id == 212 || $data->process_id == 215 ||$data->process_id == 220 || $data->process_id == 222 || $data->process_id == 223 )
            <bajaj-audit-bot-sheet qm-sheet-id="{{ $qm_sheet_id }}" today-date="{{ date('d-m-Y') }}"
                auditor-name="{{ Auth::user()->name }}" auditor-id="{{ Crypt::encrypt(Auth::user()->id) }}">
            </bajaj-audit-bot-sheet>
            @elseif($data->process_id == 191 ||$data->process_id == 199 || $data->process_id == 200 || $data->process_id == 204 || $data->process_id == 208 || $data->process_id == 206 || $data->process_id == 205 || $data->process_id == 216 || $data->process_id == 286)
            <bajaj-partner-component qm-sheet-id="{{ $qm_sheet_id }}" today-date="{{ date('d-m-Y') }}"
                auditor-name="{{ Auth::user()->name }}" auditor-id="{{ Crypt::encrypt(Auth::user()->id) }}">
            </bajaj-partner-component>


            @elseif($data->process_id == 217 ||$data->process_id == 218 )
            <voice-audit-component qm-sheet-id="{{ $qm_sheet_id }}" today-date="{{ date('d-m-Y') }}"
                auditor-name="{{ Auth::user()->name }}"
                auditor-id="{{ Crypt::encrypt(Auth::user()->id) }}">
            </voice-audit-component>

            @elseif($data->process_id == 224 ||$data->process_id == 225 || $data->process_id == 226 || $data->process_id == 227 || $data->process_id == 228 || $data->process_id == 229 || $data->process_id == 243 ||$data->process_id == 244 ||$data->process_id == 245 ||$data->process_id == 246 ||$data->process_id == 247 ||$data->process_id == 248 ||$data->process_id == 249 ||$data->process_id == 250 ||$data->process_id == 251 ||$data->process_id == 252 ||$data->process_id == 253 ||$data->process_id == 254 || $data->process_id == 255 ||$data->process_id == 256 ||$data->process_id == 257 ||$data->process_id == 258 || $data->process_id == 259 ||$data->process_id == 260 || $data->process_id == 264 || $data->process_id == 267 || $data->process_id == 274||  $data->process_id == 275||  $data->process_id == 276 || $data->process_id == 277 || $data->process_id == 278 || $data->process_id == 279 || $data->process_id == 280 || $data->process_id == 281 || $data->process_id == 285    )
            <rcc-bfl-audit-component qm-sheet-id="{{ $qm_sheet_id }}" today-date="{{ date('d-m-Y') }}"
                auditor-name="{{ Auth::user()->name }}"
                auditor-id="{{ Crypt::encrypt(Auth::user()->id) }}">
            </rcc-bfl-audit-component>

            @elseif($data->process_id == 230 || $data->process_id == 232 || $data->process_id == 233 || $data->process_id == 234 || $data->process_id == 235 || $data->process_id == 236 || $data->process_id == 237 || $data->process_id == 238 || $data->process_id == 239 || $data->process_id == 240 || $data->process_id == 241 || $data->process_id == 242  || $data->process_id == 262 ||  $data->process_id == 272 || $data->process_id == 261 || $data->process_id == 263 ||  $data->process_id == 282||$data->process_id == 269 || $data->process_id == 284  || $data->process_id == 268 || $data->process_id == 270|| $data->process_id == 283 ||$data->process_id == 271  )
            <rcc-bfl-audit-auto-component qm-sheet-id="{{ $qm_sheet_id }}" today-date="{{ date('d-m-Y') }}"
                auditor-name="{{ Auth::user()->name }}"
                auditor-id="{{ Crypt::encrypt(Auth::user()->id) }}">
            </rcc-bfl-audit-auto-component>


            @else
            <audit qm-sheet-id="{{ $qm_sheet_id }}" today-date="{{ date('d-m-Y') }}"
                auditor-name="{{ Auth::user()->name }}" auditor-id="{{ Crypt::encrypt(Auth::user()->id) }}">
            </audit>
            @endif

        </div>

    </div>
    <script>
        var KTAppOptions = {
            "colors": {
                "state": {
                    "brand": "#5d78ff",
                    "dark": "#282a3c",
                    "light": "#ffffff",
                    "primary": "#5867dd",
                    "success": "#34bfa3",
                    "info": "#36a3f7",
                    "warning": "#ffb822",
                    "danger": "#fd3995"
                },
                "base": {
                    "label": ["#c5cbe3", "#a1a8c3", "#3d4465", "#3e4466"],
                    "shape": ["#f0f3ff", "#d9dffa", "#afb4d4", "#646c9a"]
                }
            }
        };
    </script>

    <script type="text/javascript" src="/js/app.js"></script>
    <!--begin::Global Theme Bundle(used by all pages) -->
    {!! Html::script('assets/vendors/base/vendors.bundle.js') !!}
    {!! Html::script('assets/demo/default/base/scripts.bundle.js') !!}
    {!! Html::script('assets/app/custom/general/dashboard.js') !!}
    <!--end::Page Scripts -->
    {!! Html::script('assets/app/bundle/app.bundle.js') !!}

    {!! Html::script('assets/app/custom/general/crud/forms/widgets/bootstrap-datetimepicker.js') !!}

    <script type="text/javascript">
        function redirect(value) {

            var sheet_id = value.trim();
            var url = '/audit_sheet/' + sheet_id;
            console.log(sheet_id);
            if (value != '') {
                window.location = url;
            }
        }
    </script>

</body>

<!-- end::Body -->

</html>