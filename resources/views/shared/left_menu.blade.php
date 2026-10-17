<!-- begin:: Aside -->

<button class="kt-aside-close " id="kt_aside_close_btn"><i class="la la-close"></i></button>

<div class="kt-aside  kt-aside--fixed  kt-grid__item kt-grid kt-grid--desktop kt-grid--hor-desktop" id="kt_aside">



    <!-- begin:: Aside -->

    <div class="kt-aside__brand kt-grid__item " id="kt_aside_brand">

        <div class="kt-aside__brand-logo">

            <a href="{{ url('home') }}">

                <img alt="Logo" src="{{ url('assets/media/logos/logo-light.png') }}" />

            </a>

        </div>

        <div class="kt-aside__brand-tools">

            <button class="kt-aside__brand-aside-toggler" id="kt_aside_toggler">

                <span><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                        height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">

                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">

                            <polygon id="Shape" points="0 0 24 0 24 24 0 24" />

                            <path
                                d="M5.29288961,6.70710318 C4.90236532,6.31657888 4.90236532,5.68341391 5.29288961,5.29288961 C5.68341391,4.90236532 6.31657888,4.90236532 6.70710318,5.29288961 L12.7071032,11.2928896 C13.0856821,11.6714686 13.0989277,12.281055 12.7371505,12.675721 L7.23715054,18.675721 C6.86395813,19.08284 6.23139076,19.1103429 5.82427177,18.7371505 C5.41715278,18.3639581 5.38964985,17.7313908 5.76284226,17.3242718 L10.6158586,12.0300721 L5.29288961,6.70710318 Z"
                                id="Path-94" fill="#000000" fill-rule="nonzero"
                                transform="translate(8.999997, 11.999999) scale(-1, 1) translate(-8.999997, -11.999999) " />

                            <path
                                d="M10.7071009,15.7071068 C10.3165766,16.0976311 9.68341162,16.0976311 9.29288733,15.7071068 C8.90236304,15.3165825 8.90236304,14.6834175 9.29288733,14.2928932 L15.2928873,8.29289322 C15.6714663,7.91431428 16.2810527,7.90106866 16.6757187,8.26284586 L22.6757187,13.7628459 C23.0828377,14.1360383 23.1103407,14.7686056 22.7371482,15.1757246 C22.3639558,15.5828436 21.7313885,15.6103465 21.3242695,15.2371541 L16.0300699,10.3841378 L10.7071009,15.7071068 Z"
                                id="Path-94" fill="#000000" fill-rule="nonzero" opacity="0.3"
                                transform="translate(15.999997, 11.999999) scale(-1, 1) rotate(-270.000000) translate(-15.999997, -11.999999) " />

                        </g>

                    </svg></span>

                <span><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                        height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">

                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">

                            <polygon id="Shape" points="0 0 24 0 24 24 0 24" />

                            <path
                                d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z"
                                id="Path-94" fill="#000000" fill-rule="nonzero" />

                            <path
                                d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z"
                                id="Path-94" fill="#000000" fill-rule="nonzero" opacity="0.3"
                                transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) " />

                        </g>

                    </svg></span>

            </button>



            <!--

<button class="kt-aside__brand-aside-toggler kt-aside__brand-aside-toggler--left" id="kt_aside_toggler"><span></span></button>

-->

        </div>

    </div>



    <!-- end:: Aside -->



    <!-- begin:: Aside Menu -->

    <div class="kt-aside-menu-wrapper kt-grid__item kt-grid__item--fluid" id="kt_aside_menu_wrapper">

        <div id="kt_aside_menu" class="kt-aside-menu " data-ktmenu-vertical="1" data-ktmenu-scroll="1"
            data-ktmenu-dropdown-timeout="500">





            <?php if (Auth::user()->is_first_time_user == 0) { ?>

                <ul class="kt-menu__nav ">

                    <li class="kt-menu__item " aria-haspopup="true"><a href="{{ url('/home') }}" class="kt-menu__link "><i
                                class="kt-menu__link-icon flaticon-home"></i><span
                                class="kt-menu__link-text">Dashboard</span></a></li>

                    @if (Auth::user()->hasRole('super-admin'))




                    <li class="kt-menu__section ">

                        <h4 class="kt-menu__section-text">ACL</h4>

                        <i class="kt-menu__section-icon flaticon-more-v2"></i>

                    </li>

                    <li class="kt-menu__item " aria-haspopup="true"><a href="/permission" class="kt-menu__link "><i
                                class="kt-menu__link-icon flaticon-home"></i><span
                                class="kt-menu__link-text">Permissions</span></a></li>

                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"
                        data-ktmenu-submenu-toggle="hover"><a href="javascript:;"
                            class="kt-menu__link kt-menu__toggle"><i
                                class="kt-menu__link-icon flaticon-layers"></i><span
                                class="kt-menu__link-text">Role</span><i
                                class="kt-menu__ver-arrow la la-angle-right"></i></a>

                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

                            <ul class="kt-menu__subnav">

                                <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span
                                        class="kt-menu__link"><span class="kt-menu__link-text">Role</span></span></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a href="role/create"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Create</span></a></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a href="/role"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Manage</span></a></li>

                            </ul>

                        </div>

                    </li>

                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"
                        data-ktmenu-submenu-toggle="hover"><a href="javascript:;"
                            class="kt-menu__link kt-menu__toggle"><i
                                class="kt-menu__link-icon flaticon-layers"></i><span
                                class="kt-menu__link-text">User</span><i
                                class="kt-menu__ver-arrow la la-angle-right"></i></a>

                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

                            <ul class="kt-menu__subnav">

                                <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span
                                        class="kt-menu__link"><span class="kt-menu__link-text">User</span></span></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a href="/user/create"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Create</span></a></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a href="/user"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Manage</span></a></li>

                            </ul>

                        </div>

                    </li>

                    <li class="kt-menu__section ">

                        <h4 class="kt-menu__section-text">Actions</h4>

                        <i class="kt-menu__section-icon flaticon-more-v2"></i>

                    </li>

                    <li class="kt-menu__item " aria-haspopup="true"><a href="/company" class="kt-menu__link "><i
                                class="kt-menu__link-icon flaticon-home"></i><span
                                class="kt-menu__link-text">Subscribers</span></a></li>
                    @elseif(Auth::user()->hasRole('qa'))
                    @if (Auth::user()->assigned_sheet_id)
                    <li class="kt-menu__item " aria-haspopup="true"><a
                            href="/audit_sheet/{{ Crypt::encrypt(Auth::user()->assigned_sheet_id) }}"
                            class="kt-menu__link "><i class="kt-menu__link-icon flaticon-home"></i><span
                                class="kt-menu__link-text">Audit-Sheet</span></a></li>
                    @else
                    <li class="kt-menu__item " aria-haspopup="true"><a href="#" class="kt-menu__link "><i
                                class="kt-menu__link-icon flaticon-home"></i><span
                                class="kt-menu__link-text">Audit-Sheet</span></a></li>
                    @endif

                    <li class="kt-menu__item " aria-haspopup="true"><a href="/raised_rebuttal_list"
                            class="kt-menu__link "><i class="kt-menu__link-icon flaticon-home"></i><span
                                class="kt-menu__link-text">My Rebuttal</span></a></li>


                    <li class="kt-menu__item " aria-haspopup="true"><a href="/agent_downTime"
                            class="kt-menu__link "><i class="kt-menu__link-icon flaticon-home"></i><span
                                class="kt-menu__link-text">Agent Down Time</span></a></li>

                    <li class="kt-menu__item " aria-haspopup="true"><a href="/qc_qa/audits"
                            class="kt-menu__link "><i class="kt-menu__link-icon flaticon-home"></i><span
                                class="kt-menu__link-text">QC Feedback Audits</span></a></li>

                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"
                        data-ktmenu-submenu-toggle="hover"><a href="javascript:;"
                            class="kt-menu__link kt-menu__toggle"><i
                                class="kt-menu__link-icon flaticon-layers"></i><span
                                class="kt-menu__link-text">Audited Data</span><i
                                class="kt-menu__ver-arrow la la-angle-right"></i></a>

                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

                            @if (Auth::user()->assigned_sheet_id)
                            <ul class="kt-menu__subnav">

                                <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span
                                        class="kt-menu__link"><span class="kt-menu__link-text">Audited
                                            Data</span></span></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a
                                        href="/audited_list/{{ Crypt::encrypt(Auth::user()->assigned_sheet_id) }}"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Main Pool</span></a></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a
                                        href="/tmp_audited_list/{{ Crypt::encrypt(Auth::user()->assigned_sheet_id) }}"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Temp Pool</span></a></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a href="/audit_cases_list"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Create Cases</span></a></li>
                                <li class="kt-menu__item " aria-haspopup="true"><a href="/list-cases"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Show Cases</span></a></li>


                            </ul>
                            @else
                            <ul class="kt-menu__subnav">

                                <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span
                                        class="kt-menu__link"><span class="kt-menu__link-text">Audit
                                            Data</span></span></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a href="#"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Main Pool</span></a></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a href="#"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Temp Pool</span></a></li>

                            </ul>
                            @endif

                        </div>

                    </li>
                    @elseif(Auth::user()->hasRole('client'))
                    @if (Auth::user()->id == 238)
                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"
                        data-ktmenu-submenu-toggle="hover"><a href="javascript:;"
                            class="kt-menu__link kt-menu__toggle"><i
                                class="kt-menu__link-icon flaticon-layers"></i><span
                                class="kt-menu__link-text">Randomizer</span><i
                                class="kt-menu__ver-arrow la la-angle-right"></i></a>

                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

                            <ul class="kt-menu__subnav">

                                <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span
                                        class="kt-menu__link"><span
                                            class="kt-menu__link-text">Randomizer</span></span></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a href="/randomizer_report"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Randomizer Samples List</span></a></li>
                                <li class="kt-menu__item " aria-haspopup="true"><a href="/set"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Set Randomizer Sample Rules</span></a></li>


                            </ul>

                        </div>

                    </li>
                    @endif



                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"
                        data-ktmenu-submenu-toggle="hover"><a href="javascript:;"
                            class="kt-menu__link kt-menu__toggle"><i
                                class="kt-menu__link-icon flaticon-layers"></i><span
                                class="kt-menu__link-text">Report</span><i
                                class="kt-menu__ver-arrow la la-angle-right"></i></a>

                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

                            <ul class="kt-menu__subnav">

                                <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span
                                        class="kt-menu__link"><span class="kt-menu__link-text">Report</span></span>
                                </li>

                                <li class="kt-menu__item " aria-haspopup="true"><a
                                        href="/report/new_parameter_compliance" class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Disposition & Parameter compliance</span></a>
                                </li>

                                <!--<li class="kt-menu__item " aria-haspopup="true"><a href="/report/parameter_compliance" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Disposition & Parameter compliance</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="/report/agent_compliance" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Agent Compliance</span></a></li>-->

                                <li class="kt-menu__item " aria-haspopup="true"><a
                                        href="/report/raw_dump_with_audit_report" class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Raw Dump with Audit Report</span></a></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a
                                        href="/report/rebuttal_status_report" class="kt-menu__link"><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Rebuttal Report</span></a></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a
                                        href="/report/agent_performance_report" class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Agent Performance Quartile Report</span></a>
                                </li>
                                @if (Auth::user()->id == 3603 || Auth::user()->id == 636 || Auth::user()->id == 3995 ||  Auth::user()->id == 3560)
                                {{-- @if (Auth::user()->id == 3603) --}}
                                <li class="kt-menu__item " aria-haspopup="true"><a
                                        href="/report/raw_dump_download" class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Raw Data Download Process Wise</span></a>
                                </li>
                                @endif

                                <li class="kt-menu__item " aria-haspopup="true"><a
                                        href="/report/monthly_trending_report" class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Monthly Trending Report</span></a></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a
                                        href="/report/new_agent_compliance" class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Agent Compliance</span></a></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a href="/report/rebuttal_summary"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Rebuttal Summary</span></a></li>


                                <li class="kt-menu__item " aria-haspopup="true"><a
                                        href="/report/agent_feedback_report" class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Agent Feedback Report</span></a></li>


                                @if (Auth::user()->parent_client == 23)
                                <li class="kt-menu__item " aria-haspopup="true"><a
                                        href="/report/agent_feedback_report_download" class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Agent Feedback Report Download</span></a>
                                </li>
                                @endif

                            </ul>

                        </div>

                    </li>
                    @elseif(Auth::user()->hasRole('qc'))
                    <li class="kt-menu__item " aria-haspopup="true"><a href="/qc/audits" class="kt-menu__link "><i
                                class="kt-menu__link-icon flaticon-home"></i><span
                                class="kt-menu__link-text">Audits</span></a></li>

                    <li class="kt-menu__item " aria-haspopup="true"><a href="/report/qc_report"
                            class="kt-menu__link "><i class="kt-menu__link-icon flaticon-home"></i><span
                                class="kt-menu__link-text">QC Report</span></a></li>
                    <li class="kt-menu__item " aria-haspopup="true"><a href="/report/qc_report_parameters"
                            class="kt-menu__link "><i
                                class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                class="kt-menu__link-text">QC- QA Parameter Wise report</span></a></li>

                    <li class="kt-menu__item " aria-haspopup="true"><a href="/report/qc_qa_feedbacks"
                            class="kt-menu__link "><i
                                class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                class="kt-menu__link-text">QC- QA feedbacks</span></a></li>


                    @if (Auth::user()->parent_client = 23)
                    <li class="kt-menu__item " aria-haspopup="true"><a href="/report/raw_dump_download"
                            class="kt-menu__link "><i
                                class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                class="kt-menu__link-text">Raw Data Download Process Wise</span></a></li>
                    @endif

                    <li class="kt-menu__item " aria-haspopup="true"><a href="/raised_rebuttal_list"
                            class="kt-menu__link "><i class="kt-menu__link-icon flaticon-home"></i><span
                                class="kt-menu__link-text">Raised Rebuttals</span></a></li>
                    @elseif(Auth::user()->hasRole('partner-training-head') ||
                    Auth::user()->hasRole('partner-operation-head') ||
                    Auth::user()->hasRole('partner-quality-head') ||
                    Auth::user()->hasRole('partner-admin') ||
                    Auth::user()->hasRole('partner-qa'))
                    <li class="kt-menu__item " aria-haspopup="true"><a href="/partner/audit/completed"
                            class="kt-menu__link "><i class="kt-menu__link-icon flaticon-home"></i><span
                                class="kt-menu__link-text">Audits</span></a></li>

                    <!-- <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon-layers"></i><span class="kt-menu__link-text">Randomizer</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>

 <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

  <ul class="kt-menu__subnav">

   <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Randomizer</span></span></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="/randomizer_report" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Randomizer Samples List</span></a></li>
   <li class="kt-menu__item " aria-haspopup="true"><a href="/set" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Set Randomizer Sample Rules</span></a></li>


  </ul>

 </div>

</li> -->

                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"
                        data-ktmenu-submenu-toggle="hover"><a href="javascript:;"
                            class="kt-menu__link kt-menu__toggle"><i
                                class="kt-menu__link-icon flaticon-layers"></i><span
                                class="kt-menu__link-text">Reports</span><i
                                class="kt-menu__ver-arrow la la-angle-right"></i></a>

                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

                            <ul class="kt-menu__subnav">

                                <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span
                                        class="kt-menu__link"><span class="kt-menu__link-text">Reports</span></span>
                                </li>

                                <li class="kt-menu__item " aria-haspopup="true"><a
                                        href="/report/raw_dump_with_audit_report" class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Raw Dump with Audit Report</span></a></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a
                                        href="/report/rebuttal_status_report" class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Rebuttal Report</span></a></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a
                                        href="/report/new_agent_compliance" class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Agent Compliance</span></a></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a
                                        href="/report/new_parameter_compliance" class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Disposition & Parameter compliance</span></a>
                                </li>

                                <li class="kt-menu__item " aria-haspopup="true"><a
                                        href="/report/agent_feedback_report" class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Agent Feedback Report</span></a></li>

                                {{-- <li class="kt-menu__item " aria-haspopup="true"><a href="/report/agent_feedback_report_download" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Agent Feedback Report Download</span></a></li> --}}

                            </ul>

                        </div>

                    </li>

                    {{-- Apply training --}}


                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"
                        data-ktmenu-submenu-toggle="hover"><a href="{{ url('/apply_training') }}"
                            class="kt-menu__link "><i class="kt-menu__link-icon flaticon-layers"></i><span
                                class="kt-menu__link-text">Apply Training</span></a>


                    </li>

                    @if(Auth::user()->id == 4169 || Auth::user()->id == 4171 || Auth::user()->id == 4203 || Auth::user()->id == 4202 || Auth::user()->id == 4173 || Auth::user()->id == 4201 || Auth::user()->id == 4170 || Auth::user()->id == 4168 || Auth::user()->id == 4174 || Auth::user()->id == 4172 || Auth::user()->id == 4200 || Auth::user()->id == 4177 || Auth::user()->id == 4175 || Auth::user()->id == 4198 ||  Auth::user()->id == 4196 )


                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"
                        data-ktmenu-submenu-toggle="hover"><a href="/allocation/dump_uploader"
                            class="kt-menu__link "><i class="kt-menu__link-icon flaticon-layers"></i><span
                                class="kt-menu__link-text">Agents / Call Dump Uploader</span></a>


                    </li>

                    @endif

                    @else
                    <li class="kt-menu__section ">

                        <h4 class="kt-menu__section-text">ACL</h4>

                        <i class="kt-menu__section-icon flaticon-more-v2"></i>

                    </li>

                    <li class="kt-menu__item " aria-haspopup="true"><a href="/permission" class="kt-menu__link "><i
                                class="kt-menu__link-icon flaticon-home"></i><span
                                class="kt-menu__link-text">Permissions</span></a></li>

                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"
                        data-ktmenu-submenu-toggle="hover"><a href="javascript:;"
                            class="kt-menu__link kt-menu__toggle"><i
                                class="kt-menu__link-icon flaticon-layers"></i><span
                                class="kt-menu__link-text">Role</span><i
                                class="kt-menu__ver-arrow la la-angle-right"></i></a>

                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

                            <ul class="kt-menu__subnav">

                                <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span
                                        class="kt-menu__link"><span class="kt-menu__link-text">Role</span></span></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a href="role/create"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Create</span></a></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a href="/role"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Manage</span></a></li>

                            </ul>

                        </div>

                    </li>


                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"
                        data-ktmenu-submenu-toggle="hover"><a href="javascript:;"
                            class="kt-menu__link kt-menu__toggle"><i
                                class="kt-menu__link-icon flaticon-layers"></i><span
                                class="kt-menu__link-text">User</span><i
                                class="kt-menu__ver-arrow la la-angle-right"></i></a>

                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

                            <ul class="kt-menu__subnav">

                                <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span
                                        class="kt-menu__link"><span class="kt-menu__link-text">User</span></span></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a href="/user/create"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Create</span></a></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a href="/user"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Manage</span></a></li>
                                @if (Auth::user()->id == 3497)
                                <li class="kt-menu__item " aria-haspopup="true"><a href="/password_update"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Update Password</span></a></li>

                                @endif



                            </ul>

                        </div>

                    </li>


                    <li class="kt-menu__section ">

                        <h4 class="kt-menu__section-text">Actions</h4>

                        <i class="kt-menu__section-icon flaticon-more-v2"></i>

                    </li>
                    {{-- Schedule taining --}}


                    @if (Auth::user()->is_sme == 1)
                    {{-- <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"
                          data-ktmenu-submenu-toggle="hover"><a href="/schedule_training"
                              class="kt-menu__link kt-menu__toggle"><i
                                  class="kt-menu__link-icon flaticon-layers"></i><span
                                  class="kt-menu__link-text">Schedule Training</span><i
                                  class="kt-menu__ver-arrow la la-angle-right"></i></a>



                      </li> --}}

                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"
                        data-ktmenu-submenu-toggle="hover"><a href="javascript:;"
                            class="kt-menu__link kt-menu__toggle"><i
                                class="kt-menu__link-icon flaticon-layers"></i><span
                                class="kt-menu__link-text">Schedule Training</span><i
                                class="kt-menu__ver-arrow la la-angle-right"></i></a>

                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

                            <ul class="kt-menu__subnav">


                                <li class="kt-menu__item " aria-haspopup="true"><a href="/schedule_training"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Schedule Training</span></a></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a href="/update_training_list"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Training History</a></li>
                                <li class="kt-menu__item " aria-haspopup="true"><a href="/update_training"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Update Training Time</a></li>

                            </ul>
                        </div>
                    </li>
                    @endif

                    @if (Auth::user()->hasRole('qtl'))
                    <?php if (Auth::user()->parent_client == 23) { ?>

                        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"
                            data-ktmenu-submenu-toggle="hover"><a href="javascript:;"
                                class="kt-menu__link kt-menu__toggle"><i
                                    class="kt-menu__link-icon flaticon-layers"></i><span
                                    class="kt-menu__link-text">CASES</span><i
                                    class="kt-menu__ver-arrow la la-angle-right"></i></a>

                            <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

                                <ul class="kt-menu__subnav">

                                    {{-- <li class="kt-menu__item " aria-haspopup="true"><a href="/qtl_dash_cases" class="kt-menu__link "><i class="kt-menu__link-icon flaticon-layers"></i><span class="kt-menu__link-text">Cases Dashboard</span></a></li> --}}

                                    <li class="kt-menu__item " aria-haspopup="true"><a href="/qtl_dash_cases"
                                            class="kt-menu__link "><i
                                                class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                                class="kt-menu__link-text">Cases Dashboard</span></a></li>

                                    <li class="kt-menu__item " aria-haspopup="true"><a href="/list-cases"
                                            class="kt-menu__link "><i
                                                class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                                class="kt-menu__link-text">Show Cases</a></li>

                                </ul>
                            </div>


                        </li>


                        <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"
                            data-ktmenu-submenu-toggle="hover"><a href="javascript:;"
                                class="kt-menu__link kt-menu__toggle"><i
                                    class="kt-menu__link-icon flaticon-layers"></i><span class="kt-menu__link-text">QA
                                    Performance</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>

                            <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

                                <ul class="kt-menu__subnav">

                                    <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span
                                            class="kt-menu__link"><span class="kt-menu__link-text">QA
                                                Performance</span></span></li>

                                    <li class="kt-menu__item " aria-haspopup="true"><a
                                            href="/qa_performance_sixMonth" class="kt-menu__link "><i
                                                class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                                class="kt-menu__link-text">Last 6 Month Performance</span></a></li>
                                    <li class="kt-menu__item " aria-haspopup="true"><a href="/qa_performance_search"
                                            class="kt-menu__link "><i
                                                class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                                class="kt-menu__link-text">Current Month</span></a></li>


                                </ul>

                            </div>

                        </li>

                        <li class="kt-menu__item " aria-haspopup="true"><a href="/raised_rebuttal_list"
                                class="kt-menu__link "><i class="kt-menu__link-icon flaticon-home"></i><span
                                    class="kt-menu__link-text">My Rebuttal</span></a></li>

                    <?php }  ?>



                    @if (Auth::user()->is_sme == 1)
                    <li class="kt-menu__item " aria-haspopup="true"><a href="/edit_lob_get"
                            class="kt-menu__link "><i class="kt-menu__link-icon flaticon-layers"></i><span
                                class="kt-menu__link-text">Edit LoB</span></a></li>
                    @endif



                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"
                        data-ktmenu-submenu-toggle="hover"><a href="javascript:;"
                            class="kt-menu__link kt-menu__toggle"><i
                                class="kt-menu__link-icon flaticon-layers"></i><span class="kt-menu__link-text">QA
                                Down Time</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>

                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

                            <ul class="kt-menu__subnav">

                                <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span
                                        class="kt-menu__link"><span class="kt-menu__link-text">QA Down
                                            Time</span></span></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a href="/check/down_time"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Check QA Down Time</span></a></li>


                            </ul>

                        </div>

                    </li>






                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"
                        data-ktmenu-submenu-toggle="hover"><a href="javascript:;"
                            class="kt-menu__link kt-menu__toggle"><i
                                class="kt-menu__link-icon flaticon-layers"></i><span class="kt-menu__link-text">QA
                                Target</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>

                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

                            <ul class="kt-menu__subnav">

                                <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span
                                        class="kt-menu__link"><span class="kt-menu__link-text">QA
                                            Target</span></span></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a href="/qa_target/set"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Set QA Target</span></a></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a href="/qa_target/list"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Manage </span></a></li>

                            </ul>

                        </div>

                    </li>

                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"
                        data-ktmenu-submenu-toggle="hover"><a href="javascript:;"
                            class="kt-menu__link kt-menu__toggle"><i
                                class="kt-menu__link-icon flaticon-layers"></i><span
                                class="kt-menu__link-text">Agent Feeback Email</span><i
                                class="kt-menu__ver-arrow la la-angle-right"></i></a>
                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                            <ul class="kt-menu__subnav">
                                <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span
                                        class="kt-menu__link"><span class="kt-menu__link-text">Agent Feeback
                                            Email</span></span></li>
                                <li class="kt-menu__item " aria-haspopup="true"><a
                                        href="/agent_feedback_email/set" class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Add Email</span></a></li>
                                <li class="kt-menu__item " aria-haspopup="true"><a
                                        href="/agent_feedback_email/list" class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Manage</span></a></li>
                            </ul>
                        </div>
                    </li>



                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"
                        data-ktmenu-submenu-toggle="hover"><a href="javascript:;"
                            class="kt-menu__link kt-menu__toggle"><i
                                class="kt-menu__link-icon flaticon-layers"></i><span
                                class="kt-menu__link-text">Cluster Role Mapping</span><i
                                class="kt-menu__ver-arrow la la-angle-right"></i></a>

                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

                            <ul class="kt-menu__subnav">

                                <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span
                                        class="kt-menu__link"><span class="kt-menu__link-text">Week
                                            Definitions</span></span></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a href="/role_mapping/list"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Mapping</span></a></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a href="/qa_allocation"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Allocation To Auditors</span></a></li>

                            </ul>

                        </div>

                    </li>

                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"
                        data-ktmenu-submenu-toggle="hover"><a href="javascript:;"
                            class="kt-menu__link kt-menu__toggle"><i
                                class="kt-menu__link-icon flaticon-layers"></i><span
                                class="kt-menu__link-text">Randomizer</span><i
                                class="kt-menu__ver-arrow la la-angle-right"></i></a>

                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

                            <ul class="kt-menu__subnav">

                                <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span
                                        class="kt-menu__link"><span
                                            class="kt-menu__link-text">Randomizer</span></span></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a href="/randomizer_report"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Randomizer Samples List</span></a></li>
                                <li class="kt-menu__item " aria-haspopup="true"><a href="/set"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Set Randomizer Sample Rules</span></a></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a href="/qa_allocation"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Allocation To Auditors</span></a></li>

                            </ul>

                        </div>

                    </li>

                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"
                        data-ktmenu-submenu-toggle="hover"><a href="javascript:;"
                            class="kt-menu__link kt-menu__toggle"><i
                                class="kt-menu__link-icon flaticon-layers"></i><span class="kt-menu__link-text">QA
                                Daily Target</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>

                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

                            <ul class="kt-menu__subnav">

                                <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span
                                        class="kt-menu__link"><span class="kt-menu__link-text">QA Daily
                                            Target</span></span></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a href="/qa_daily_target/set"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Set QA Daily Target</span></a></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a href="/qa_daily_target/list"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Manage </span></a></li>

                            </ul>

                        </div>

                    </li>



                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"
                        data-ktmenu-submenu-toggle="hover"><a href="javascript:;"
                            class="kt-menu__link kt-menu__toggle"><i
                                class="kt-menu__link-icon flaticon-layers"></i><span
                                class="kt-menu__link-text">Training PKT</span><i
                                class="kt-menu__ver-arrow la la-angle-right"></i></a>

                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

                            <ul class="kt-menu__subnav">

                                <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span
                                        class="kt-menu__link"><span class="kt-menu__link-text">Training
                                            PKT</span></span></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a href="/trainning_pkt/set"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Upload Training PKT </span></a></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a href="/trainning_pkt/list"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Manage </span></a></li>

                            </ul>

                        </div>

                    </li>
                    @endif







                    {{-- <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon-layers"></i><span class="kt-menu__link-text">Audit Alert Box</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a> --}}

                    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

                        <ul class="kt-menu__subnav">

                            <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span
                                    class="kt-menu__link"><span class="kt-menu__link-text">Audit Alert
                                        Box</span></span></li>

                            <li class="kt-menu__item " aria-haspopup="true"><a href="/audit_alert_box/create"
                                    class="kt-menu__link "><i
                                        class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                        class="kt-menu__link-text">Create</span></a></li>

                            <li class="kt-menu__item " aria-haspopup="true"><a href="/audit_alert_box"
                                    class="kt-menu__link "><i
                                        class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                        class="kt-menu__link-text">Manage</span></a></li>

                        </ul>

                    </div>

                    </li>





                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"
                        data-ktmenu-submenu-toggle="hover"><a href="javascript:;"
                            class="kt-menu__link kt-menu__toggle"><i
                                class="kt-menu__link-icon flaticon-layers"></i><span class="kt-menu__link-text">Audit
                                Cycle</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>

                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

                            <ul class="kt-menu__subnav">

                                <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span
                                        class="kt-menu__link"><span class="kt-menu__link-text">Audit
                                            Cycle</span></span></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a href="/audit_cycle/create"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Create</span></a></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a href="/audit_cycle"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Manage</span></a></li>

                            </ul>

                        </div>

                    </li>

                    <?php if (Auth::user()->id == 39) { ?>



                        <li class="kt-menu__item " aria-haspopup="true"><a href="{{ route('emi-calculation') }}"
                                class="kt-menu__link "><i class="kt-menu__link-icon flaticon-home"></i><span
                                    class="kt-menu__link-text">Emi Calculator</span></a></li>



                    <?php }  ?>






                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"
                        data-ktmenu-submenu-toggle="hover"><a href="javascript:;"
                            class="kt-menu__link kt-menu__toggle"><i
                                class="kt-menu__link-icon flaticon-layers"></i><span
                                class="kt-menu__link-text">Process</span><i
                                class="kt-menu__ver-arrow la la-angle-right"></i></a>

                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

                            <ul class="kt-menu__subnav">

                                <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span
                                        class="kt-menu__link"><span class="kt-menu__link-text">Process</span></span>
                                </li>

                                <li class="kt-menu__item " aria-haspopup="true"><a href="/process/create"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Create</span></a></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a href="/process"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Manage</span></a></li>

                            </ul>

                        </div>

                    </li>


                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"
                        data-ktmenu-submenu-toggle="hover"><a href="javascript:;"
                            class="kt-menu__link kt-menu__toggle"><i
                                class="kt-menu__link-icon flaticon-layers"></i><span
                                class="kt-menu__link-text">Region</span><i
                                class="kt-menu__ver-arrow la la-angle-right"></i></a>

                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

                            <ul class="kt-menu__subnav">

                                <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span
                                        class="kt-menu__link"><span class="kt-menu__link-text">Region</span></span>
                                </li>

                                <li class="kt-menu__item " aria-haspopup="true"><a href="/region/create"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Create</span></a></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a href="/region"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Manage</span></a></li>

                            </ul>

                        </div>

                    </li>

                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"
                        data-ktmenu-submenu-toggle="hover"><a href="javascript:;"
                            class="kt-menu__link kt-menu__toggle"><i
                                class="kt-menu__link-icon flaticon-layers"></i><span
                                class="kt-menu__link-text">Language</span><i
                                class="kt-menu__ver-arrow la la-angle-right"></i></a>

                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

                            <ul class="kt-menu__subnav">

                                <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span
                                        class="kt-menu__link"><span class="kt-menu__link-text">Language</span></span>
                                </li>

                                <li class="kt-menu__item " aria-haspopup="true"><a href="/language/create"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Create</span></a></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a href="/language"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Manage</span></a></li>

                            </ul>

                        </div>

                    </li>

                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"
                        data-ktmenu-submenu-toggle="hover"><a href="javascript:;"
                            class="kt-menu__link kt-menu__toggle"><i
                                class="kt-menu__link-icon flaticon-layers"></i><span
                                class="kt-menu__link-text">Reason</span><i
                                class="kt-menu__ver-arrow la la-angle-right"></i></a>

                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

                            <ul class="kt-menu__subnav">

                                <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span
                                        class="kt-menu__link"><span class="kt-menu__link-text">Reason</span></span>
                                </li>

                                <li class="kt-menu__item " aria-haspopup="true"><a href="/reason/create"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Create</span></a></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a href="/reason"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Manage</span></a></li>

                            </ul>

                        </div>

                    </li>

                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"
                        data-ktmenu-submenu-toggle="hover"><a href="javascript:;"
                            class="kt-menu__link kt-menu__toggle"><i
                                class="kt-menu__link-icon flaticon-layers"></i><span class="kt-menu__link-text">RCA
                                Type</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>

                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

                            <ul class="kt-menu__subnav">

                                <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span
                                        class="kt-menu__link"><span class="kt-menu__link-text">RCA Type</span></span>
                                </li>

                                <li class="kt-menu__item " aria-haspopup="true"><a href="/rca_type/create"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Create</span></a></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a href="/rca_type"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Manage</span></a></li>

                            </ul>

                        </div>

                    </li>

                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"
                        data-ktmenu-submenu-toggle="hover"><a href="javascript:;"
                            class="kt-menu__link kt-menu__toggle"><i
                                class="kt-menu__link-icon flaticon-layers"></i><span class="kt-menu__link-text">RCA
                                Mode</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>

                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

                            <ul class="kt-menu__subnav">

                                <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span
                                        class="kt-menu__link"><span class="kt-menu__link-text">RCA
                                            Mode</span></span>
                                </li>

                                <li class="kt-menu__item " aria-haspopup="true"><a href="/rca/create"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Create</span></a></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a href="/rca"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Manage</span></a></li>

                            </ul>

                        </div>

                    </li>

                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"
                        data-ktmenu-submenu-toggle="hover"><a href="javascript:;"
                            class="kt-menu__link kt-menu__toggle"><i
                                class="kt-menu__link-icon flaticon-layers"></i><span class="kt-menu__link-text">RCA2
                                Mode</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>

                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

                            <ul class="kt-menu__subnav">

                                <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span
                                        class="kt-menu__link"><span class="kt-menu__link-text">RCA2
                                            Mode</span></span>
                                </li>

                                <li class="kt-menu__item " aria-haspopup="true"><a href="/rca2/create"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Create</span></a></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a href="/rca2"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Manage</span></a></li>

                            </ul>

                        </div>

                    </li>

                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"
                        data-ktmenu-submenu-toggle="hover"><a href="javascript:;"
                            class="kt-menu__link kt-menu__toggle"><i
                                class="kt-menu__link-icon flaticon-layers"></i><span
                                class="kt-menu__link-text">Client</span><i
                                class="kt-menu__ver-arrow la la-angle-right"></i></a>

                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

                            <ul class="kt-menu__subnav">

                                <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span
                                        class="kt-menu__link"><span class="kt-menu__link-text">Client</span></span>
                                </li>

                                <!--	<li class="kt-menu__item " aria-haspopup="true"><a href="/client/create" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Create</span></a></li> -->

                                <li class="kt-menu__item " aria-haspopup="true"><a href="/client"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Manage</span></a></li>

                            </ul>

                        </div>

                    </li>

                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"
                        data-ktmenu-submenu-toggle="hover"><a href="javascript:;"
                            class="kt-menu__link kt-menu__toggle"><i
                                class="kt-menu__link-icon flaticon-layers"></i><span
                                class="kt-menu__link-text">Allocation</span><i
                                class="kt-menu__ver-arrow la la-angle-right"></i></a>

                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

                            <ul class="kt-menu__subnav">

                                <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span
                                        class="kt-menu__link"><span
                                            class="kt-menu__link-text">Allocation</span></span></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a href="/allocation/qtl_qa"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">QTL -> QA</span></a></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a href="/allocation/qa_sheet"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">QA -> QM-Sheet</span></a></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a
                                        href="/allocation/dump_uploader" class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Agents / Call Dump Uploader</span></a></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a href="/allocation/qa_target"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Set QA Target</span></a></li>



                                @if (Auth::user()->hasRole('process-owner'))
                                <li class="kt-menu__item " aria-haspopup="true"><a
                                        href="/allocation/list_month_target" class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Monthly Target</span></a></li>

                                <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"
                                    data-ktmenu-submenu-toggle="hover"><a href="javascript:;"
                                        class="kt-menu__link kt-menu__toggle"><i
                                            class="kt-menu__link-icon flaticon-layers"></i><span
                                            class="kt-menu__link-text">QA Performance</span><i
                                            class="kt-menu__ver-arrow la la-angle-right"></i></a>

                                    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

                                        <ul class="kt-menu__subnav">

                                            <li class="kt-menu__item  kt-menu__item--parent"
                                                aria-haspopup="true"><span class="kt-menu__link"><span
                                                        class="kt-menu__link-text">QA Performance</span></span>
                                            </li>

                                            <li class="kt-menu__item " aria-haspopup="true"><a
                                                    href="/qa_performance_sixMonth" class="kt-menu__link "><i
                                                        class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                                        class="kt-menu__link-text">Last 6 Month
                                                        Performance</span></a></li>
                                            <li class="kt-menu__item " aria-haspopup="true"><a
                                                    href="/qa_performance_search" class="kt-menu__link "><i
                                                        class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                                        class="kt-menu__link-text">Current Month</span></a></li>


                                        </ul>

                                    </div>

                                </li>
                                @endif

                                <!-- <li class="kt-menu__item " aria-haspopup="true"><a href="/allocation/qa_agent" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">QA -> Agents / Call Dump</span></a></li> -->

                            </ul>

                        </div>

                    </li>

                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"
                        data-ktmenu-submenu-toggle="hover"><a href="javascript:;"
                            class="kt-menu__link kt-menu__toggle"><i
                                class="kt-menu__link-icon flaticon-layers"></i><span
                                class="kt-menu__link-text">Partner Month Target</span><i
                                class="kt-menu__ver-arrow la la-angle-right"></i></a>
                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                            <ul class="kt-menu__subnav">

                                <li class="kt-menu__item " aria-haspopup="true"><a
                                        href="/allocation/month_target_uploader" class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Upload Monthly Target</span></a></li>
                                <li class="kt-menu__item " aria-haspopup="true"><a
                                        href="/allocation/monthly_partner_targets" class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Monthly Target List</span></a></li>


                                <!-- <li class="kt-menu__item " aria-haspopup="true"><a href="/allocation/qa_agent" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">QA -> Agents / Call Dump</span></a></li> -->
                            </ul>
                        </div>
                    </li>


                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"
                        data-ktmenu-submenu-toggle="hover"><a href="/allocation/raw_data_batch"
                            class="kt-menu__link kt-menu__toggle"><i
                                class="kt-menu__link-icon flaticon-layers"></i><span class="kt-menu__link-text">Raw
                                Data Batch</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a></li>



                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"
                        data-ktmenu-submenu-toggle="hover"><a href="/purge_data"
                            class="kt-menu__link kt-menu__toggle"><i
                                class="kt-menu__link-icon flaticon-layers"></i><span
                                class="kt-menu__link-text">Purge Data</span><i
                                class="kt-menu__ver-arrow la la-angle-right"></i></a></li>







                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"
                        data-ktmenu-submenu-toggle="hover"><a href="javascript:;"
                            class="kt-menu__link kt-menu__toggle"><i
                                class="kt-menu__link-icon flaticon-layers"></i><span
                                class="kt-menu__link-text">QM-Sheet</span><i
                                class="kt-menu__ver-arrow la la-angle-right"></i></a>

                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

                            <ul class="kt-menu__subnav">

                                <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span
                                        class="kt-menu__link"><span
                                            class="kt-menu__link-text">QM-Sheet</span></span></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a href="/qm_sheet/create"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Create</span></a></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a href="/qm_sheet"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Manage</span></a></li>

                            </ul>

                        </div>

                    </li>




                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"
                        data-ktmenu-submenu-toggle="hover"><a href="javascript:;"
                            class="kt-menu__link kt-menu__toggle"><i
                                class="kt-menu__link-icon flaticon-layers"></i><span
                                class="kt-menu__link-text">Cluster</span><i
                                class="kt-menu__ver-arrow la la-angle-right"></i></a>

                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

                            <ul class="kt-menu__subnav">

                                <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span
                                        class="kt-menu__link"><span class="kt-menu__link-text">Cluster</span></span>
                                </li>

                                <li class="kt-menu__item " aria-haspopup="true"><a href="/cluster/create"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Create</span></a></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a href="/cluster"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Manage</span></a></li>

                            </ul>

                        </div>

                    </li>



                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"
                        data-ktmenu-submenu-toggle="hover"><a href="javascript:;"
                            class="kt-menu__link kt-menu__toggle"><i
                                class="kt-menu__link-icon flaticon-layers"></i><span
                                class="kt-menu__link-text">Calibration</span><i
                                class="kt-menu__ver-arrow la la-angle-right"></i></a>

                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

                            <ul class="kt-menu__subnav">

                                <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span
                                        class="kt-menu__link"><span
                                            class="kt-menu__link-text">Calibration</span></span></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a href="/calibration/create"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Create</span></a></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a href="/calibration"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Manage</span></a></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a
                                        href="/calibration/my_request/list" class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Request</span></a></li>

                            </ul>

                        </div>

                    </li>



                    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"
                        data-ktmenu-submenu-toggle="hover"><a href="javascript:;"
                            class="kt-menu__link kt-menu__toggle"><i
                                class="kt-menu__link-icon flaticon-layers"></i><span
                                class="kt-menu__link-text">Reports</span><i
                                class="kt-menu__ver-arrow la la-angle-right"></i></a>

                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

                            <ul class="kt-menu__subnav">

                                <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span
                                        class="kt-menu__link"><span class="kt-menu__link-text">Reports</span></span>
                                </li>

                                @if (Auth::user()->id == 172 || Auth::user()->id == 198 || Auth::user()->id == 41)
                                <li class="kt-menu__item " aria-haspopup="true"><a
                                        href="/report/new_parameter_compliance" class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Disposition & Parameter
                                            compliance</span></a></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a
                                        href="/report/rebuttal_status_report" class="kt-menu__link"><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Rebuttal Report</span></a></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a
                                        href="/report/agent_performance_report" class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Agent Performance Quartile
                                            Report</span></a></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a
                                        href="/report/monthly_trending_report" class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Monthly Trending Report</span></a></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a
                                        href="/report/new_agent_compliance" class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Agent Compliance</span></a></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a
                                        href="/report/rebuttal_summary" class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Rebuttal Summary</span></a></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a
                                        href="/raised_rebuttal_list" class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Raised Rebuttal List</span></a></li>
                                @endif

                                <li class="kt-menu__item " aria-haspopup="true"><a
                                        href="/report/raw_dump_with_audit_report" class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Raw Dump with Audit Report</span></a></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a href="/report/qc_report"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">QC Report</span></a></li>
                                <li class="kt-menu__item " aria-haspopup="true"><a
                                        href="/report/qc_report_parameters" class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">QC- QA Parameter Wise report</span></a></li>
                                <li class="kt-menu__item " aria-haspopup="true"><a href="/report/qc_qa_feedbacks"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">QC- QA feedbacks</span></a></li>
                                @if (Auth::user()->parent_client = 23)
                                <li class="kt-menu__item " aria-haspopup="true"><a
                                        href="/report/raw_dump_download" class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Raw Data Download Process Wise</span></a>
                                </li>
                                @endif


                                @if (Auth::user()->hasRole('process-owner'))
                                <li class="kt-menu__item " aria-haspopup="true"><a
                                        href="/report/rebuttal_status_report" class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Rebuttal Report</span></a></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a
                                        href="/report/rebuttal_summary" class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Rebuttal Summary</span></a></li>
                                @endif



                                @if (Auth::user()->hasRole('qtl'))

                                @if (Auth::user()->id != 198)
                                <li class="kt-menu__item " aria-haspopup="true"><a
                                        href="/report/rebuttal_status_report" class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Rebuttal Report</span></a></li>

                                <!-- <li class="kt-menu__item " aria-haspopup="true"><a href="/report/rebuttal_summary" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Rebuttal Summary</span></a></li> -->

                                <li class="kt-menu__item " aria-haspopup="true"><a
                                        href="/qa_target/qa_report" class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">QA Report</span></a></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a
                                        href="/qa_target/qa_performance" class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">QA Performance</span></a></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a
                                        href="/qa_target/qa_performance_matrix" class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">QA Performance Matrix</span></a></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a
                                        href="/report/new_agent_compliance" class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Agent Compliance</span></a></li>

                                <!-- <li class="kt-menu__item " aria-haspopup="true"><a href="/report/agent_performance_report" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Agent Performance Quartile Report</span></a></li> -->

                                <li class="kt-menu__item " aria-haspopup="true"><a
                                        href="/report/monthly_trending_report" class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Monthly Trending Report</span></a></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a
                                        href="/report/new_parameter_compliance" class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Disposition & Parameter
                                            compliance</span></a></li>

                                <li class="kt-menu__item " aria-haspopup="true"><a
                                        href="/report/agent_feedback_report" class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Agent Feedback Report</span></a></li>


                                @if (Auth::user()->parent_client == 23)
                                <li class="kt-menu__item " aria-haspopup="true"><a
                                        href="/report/agent_feedback_report_download"
                                        class="kt-menu__link "><i
                                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                            class="kt-menu__link-text">Agent Feedback Report
                                            Download</span></a></li>
                                @endif
                                <!-- <li class="kt-menu__item " aria-haspopup="true"><a href="/three_psn/three_psn_report" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">3 PSN Report</span></a></li> -->


                                @endif
                                @endif

                            </ul>

                        </div>

                    </li>





                    @endif







                    <!-- <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--open kt-menu__item--here" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon-interface-8"></i><span class="kt-menu__link-text">General</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>

 <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

  <ul class="kt-menu__subnav">

   <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">General</span></span></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="layout/general/minimized-aside.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Minimized Aside</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="layout/general/no-aside.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">No Aside</span></a></li>

   <li class="kt-menu__item  kt-menu__item--active" aria-haspopup="true"><a href="layout/general/empty-page.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Empty Page</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="layout/general/fixed-footer.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Fixed Footer</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="layout/general/no-header-menu.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">No Header Menu</span></a></li>

  </ul>

 </div>

</li>

<li class="kt-menu__item " aria-haspopup="true"><a target="_blank" href="https://keenthemes.com/metronic/preview/default/builder.html" class="kt-menu__link "><i class="kt-menu__link-icon flaticon-cogwheel-1"></i><span class="kt-menu__link-text">Builder</span></a></li>

<li class="kt-menu__section ">

 <h4 class="kt-menu__section-text">Components</h4>

 <i class="kt-menu__section-icon flaticon-more-v2"></i>

</li>

<li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon-layers"></i><span class="kt-menu__link-text">Base</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>

 <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

  <ul class="kt-menu__subnav">

   <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Base</span></span></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="components/base/colors.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">State Colors</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="components/base/typography.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Typography</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="components/base/badge.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Badge</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="components/base/buttons.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Buttons</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="components/base/button-group.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Button Group</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="components/base/dropdown.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Dropdown</span></a></li>

   <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Tabs</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>

    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

     <ul class="kt-menu__subnav">

      <li class="kt-menu__item " aria-haspopup="true"><a href="components/base/tabs/bootstrap.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Bootstrap Tabs</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="components/base/tabs/line.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Line Tabs</span></a></li>

     </ul>

    </div>

   </li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="components/base/accordions.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Accordions</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="components/base/tables.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Tables</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="components/base/progress.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Progress</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="components/base/modal.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Modal</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="components/base/alerts.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Alerts</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="components/base/popover.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Popover</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="components/base/tooltip.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Tooltip</span></a></li>

  </ul>

 </div>

</li>

<li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon-light"></i><span class="kt-menu__link-text">Extended</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>

 <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

  <ul class="kt-menu__subnav">

   <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Extended</span></span></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="components/extended/blockui.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Block UI</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="components/extended/spinners.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Spinners</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="components/extended/perfect-scrollbar.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Perfect Scrollbar</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="components/extended/navs.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Navigations</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="components/extended/lists.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Lists</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="components/extended/timeline.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Timeline</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="components/extended/treeview.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Tree View</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="components/extended/bootstrap-notify.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Bootstrap Notify</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="components/extended/toastr.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Toastr</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="components/extended/sweetalert2.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">SweetAlert2</span></a></li>

  </ul>

 </div>

</li>

<li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon-open-box"></i><span class="kt-menu__link-text">Icons</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>

 <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

  <ul class="kt-menu__subnav">

   <li class="kt-menu__item " aria-haspopup="true"><a href="components/icons/flaticon.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Flaticon</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="components/icons/fontawesome5.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Fontawesome 5</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="components/icons/lineawesome.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Lineawesome</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="components/icons/socicons.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Socicons</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="components/icons/svg.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">SVG Icons</span></a></li>

  </ul>

 </div>

</li>

<li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon-interface-1"></i><span class="kt-menu__link-text">Portlets</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>

 <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

  <ul class="kt-menu__subnav">

   <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Portlets</span></span></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="components/portlets/base.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Base Portlets</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="components/portlets/advanced.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Advanced Portlets</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="components/portlets/tabbed.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Tabbed Portlets</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="components/portlets/draggable.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Draggable Portlets</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="components/portlets/tools.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Portlet Tools</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="components/portlets/sticky-head.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Sticky Head</span></a></li>

  </ul>

 </div>

</li>

<li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon-rocket"></i><span class="kt-menu__link-text">Widgets</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>

 <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

  <ul class="kt-menu__subnav">

   <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Widgets</span></span></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="components/widgets/lists.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Lists</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="components/widgets/charts.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Charts</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="components/widgets/general.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">General</span></a></li>

  </ul>

 </div>

</li>

<li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon-calendar"></i><span class="kt-menu__link-text">Calendar</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>

 <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

  <ul class="kt-menu__subnav">

   <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Calendar</span></span></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="components/calendar/basic.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Basic Calendar</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="components/calendar/list-view.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">List Views</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="components/calendar/google.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Google Calendar</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="components/calendar/external-events.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">External Events</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="components/calendar/background-events.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Background Events</span></a></li>

  </ul>

 </div>

</li>

<li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon-diagram"></i><span class="kt-menu__link-text">Charts</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>

 <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

  <ul class="kt-menu__subnav">

   <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Charts</span></span></li>

   <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">amCharts</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>

    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

     <ul class="kt-menu__subnav">

      <li class="kt-menu__item " aria-haspopup="true"><a href="components/charts/amcharts/charts.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">amCharts Charts</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="components/charts/amcharts/stock-charts.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">amCharts Stock Charts</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="components/charts/amcharts/maps.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">amCharts Maps</span></a></li>

     </ul>

    </div>

   </li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="components/charts/flotcharts.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Flot Charts</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="components/charts/google-charts.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Google Charts</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="components/charts/morris-charts.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Morris Charts</span></a></li>

  </ul>

 </div>

</li>

<li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon-placeholder"></i><span class="kt-menu__link-text">Maps</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>

 <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

  <ul class="kt-menu__subnav">

   <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Maps</span></span></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="components/maps/google-maps.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Google Maps</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="components/maps/jqvmap.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">JQVMap</span></a></li>

  </ul>

 </div>

</li>

<li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon-web"></i><span class="kt-menu__link-text">Utils</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>

 <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

  <ul class="kt-menu__subnav">

   <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Utils</span></span></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="components/utils/session-timeout.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Session Timeout</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="components/utils/idle-timer.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Idle Timer</span></a></li>

  </ul>

 </div>

</li>

<li class="kt-menu__section ">

 <h4 class="kt-menu__section-text">CRUD</h4>

 <i class="kt-menu__section-icon flaticon-more-v2"></i>

</li>

<li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon-interface-7"></i><span class="kt-menu__link-text">Forms</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>

 <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

  <ul class="kt-menu__subnav">

   <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Forms</span></span></li>

   <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Form Controls</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>

    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

     <ul class="kt-menu__subnav">

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/forms/controls/base.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Base Inputs</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/forms/controls/input-group.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Input Groups</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/forms/controls/checkbox.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Checkbox</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/forms/controls/radio.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Radio</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/forms/controls/switch.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Switch</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/forms/controls/option.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Mega Options</span></a></li>

     </ul>

    </div>

   </li>

   <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Form Widgets</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>

    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

     <ul class="kt-menu__subnav">

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/forms/widgets/bootstrap-datepicker.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Datepicker</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/forms/widgets/bootstrap-datetimepicker.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Datetimepicker</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/forms/widgets/bootstrap-timepicker.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Timepicker</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/forms/widgets/bootstrap-daterangepicker.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Daterangepicker</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/forms/widgets/bootstrap-touchspin.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Touchspin</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/forms/widgets/bootstrap-maxlength.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Maxlength</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/forms/widgets/bootstrap-switch.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Switch</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/forms/widgets/bootstrap-multipleselectsplitter.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Multiple Select Splitter</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/forms/widgets/bootstrap-select.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Bootstrap Select</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/forms/widgets/select2.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Select2</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/forms/widgets/typeahead.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Typeahead</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/forms/widgets/nouislider.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">noUiSlider</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/forms/widgets/form-repeater.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Form Repeater</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/forms/widgets/ion-range-slider.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Ion Range Slider</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/forms/widgets/input-mask.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Input Masks</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/forms/widgets/summernote.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Summernote WYSIWYG</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/forms/widgets/bootstrap-markdown.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Markdown Editor</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/forms/widgets/autosize.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Autosize</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/forms/widgets/clipboard.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Clipboard</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/forms/widgets/dropzone.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Dropzone</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/forms/widgets/recaptcha.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Google reCaptcha</span></a></li>

     </ul>

    </div>

   </li>

   <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Form Layouts</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>

    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

     <ul class="kt-menu__subnav">

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/forms/layouts/default-forms.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Default Forms</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/forms/layouts/multi-column-forms.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Multi Column Forms</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/forms/layouts/action-bars.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Basic Action Bars</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/forms/layouts/sticky-action-bar.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Sticky Action Bar</span></a></li>

     </ul>

    </div>

   </li>

   <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Form Validation</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>

    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

     <ul class="kt-menu__subnav">

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/forms/validation/states.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Validation States</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/forms/validation/form-controls.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Form Controls</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/forms/validation/form-widgets.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Form Widgets</span></a></li>

     </ul>

    </div>

   </li>

  </ul>

 </div>

</li>

<li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon-tabs"></i><span class="kt-menu__link-text">KTDatatable</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>

 <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

  <ul class="kt-menu__subnav">

   <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">KTDatatable</span></span></li>

   <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Base</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>

    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

     <ul class="kt-menu__subnav">

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/metronic-datatable/base/data-local.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Local Data</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/metronic-datatable/base/data-json.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">JSON Data</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/metronic-datatable/base/data-ajax.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Ajax Data</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/metronic-datatable/base/html-table.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">HTML Table</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/metronic-datatable/base/local-sort.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Local Sort</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/metronic-datatable/base/translation.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Translation</span></a></li>

     </ul>

    </div>

   </li>

   <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Advanced</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>

    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

     <ul class="kt-menu__subnav">

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/metronic-datatable/advanced/record-selection.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Record Selection</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/metronic-datatable/advanced/row-details.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Row Details</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/metronic-datatable/advanced/modal.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Modal Examples</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/metronic-datatable/advanced/column-rendering.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Column Rendering</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/metronic-datatable/advanced/column-width.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Column Width</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/metronic-datatable/advanced/vertical.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Vertical Scrolling</span></a></li>

     </ul>

    </div>

   </li>

   <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Child Datatables</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>

    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

     <ul class="kt-menu__subnav">

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/metronic-datatable/child/data-local.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Local Data</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/metronic-datatable/child/data-ajax.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Remote Data</span></a></li>

     </ul>

    </div>

   </li>

   <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">API</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>

    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

     <ul class="kt-menu__subnav">

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/metronic-datatable/api/methods.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">API Methods</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/metronic-datatable/api/events.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Events</span></a></li>

     </ul>

    </div>

   </li>

  </ul>

 </div>

</li>

<li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon-list-3"></i><span class="kt-menu__link-text">Datatables.net</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>

 <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

  <ul class="kt-menu__subnav">

   <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Datatables.net</span></span></li>

   <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Basic</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>

    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

     <ul class="kt-menu__subnav">

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/datatables/basic/basic.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Basic Tables</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/datatables/basic/scrollable.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Scrollable Tables</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/datatables/basic/headers.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Complex Headers</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/datatables/basic/paginations.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Pagination Options</span></a></li>

     </ul>

    </div>

   </li>

   <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Advanced</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>

    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

     <ul class="kt-menu__subnav">

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/datatables/advanced/column-rendering.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Column Rendering</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/datatables/advanced/multiple-controls.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Multiple Controls</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/datatables/advanced/column-visibility.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Column Visibility</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/datatables/advanced/row-callback.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Row Callback</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/datatables/advanced/row-grouping.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Row Grouping</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/datatables/advanced/footer-callback.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Footer Callback</span></a></li>

     </ul>

    </div>

   </li>

   <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Data sources</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>

    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

     <ul class="kt-menu__subnav">

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/datatables/data-sources/html.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">HTML</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/datatables/data-sources/javascript.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Javascript</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/datatables/data-sources/ajax-client-side.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Ajax Client-side</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/datatables/data-sources/ajax-server-side.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Ajax Server-side</span></a></li>

     </ul>

    </div>

   </li>

   <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Search Options</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>

    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

     <ul class="kt-menu__subnav">

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/datatables/search-options/column-search.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Column Search</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/datatables/search-options/advanced-search.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Advanced Search</span></a></li>

     </ul>

    </div>

   </li>

   <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Extensions</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>

    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

     <ul class="kt-menu__subnav">

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/datatables/extensions/buttons.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Buttons</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/datatables/extensions/colreorder.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">ColReorder</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/datatables/extensions/keytable.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">KeyTable</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/datatables/extensions/responsive.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Responsive</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/datatables/extensions/rowgroup.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">RowGroup</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/datatables/extensions/rowreorder.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">RowReorder</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/datatables/extensions/scroller.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Scroller</span></a></li>

      <li class="kt-menu__item " aria-haspopup="true"><a href="crud/datatables/extensions/select.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Select</span></a></li>

     </ul>

    </div>

   </li>

  </ul>

 </div>

</li>

<li class="kt-menu__section ">

 <h4 class="kt-menu__section-text">Custom</h4>

 <i class="kt-menu__section-icon flaticon-more-v2"></i>

</li>

<li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon-tabs"></i><span class="kt-menu__link-text">Wizard</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>

 <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

  <ul class="kt-menu__subnav">

   <li class="kt-menu__item " aria-haspopup="true"><a href="custom/wizard/wizard-v1.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Wizard v1</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="custom/wizard/wizard-v2.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Wizard v2</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="custom/wizard/wizard-v3.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Wizard v3</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="custom/wizard/wizard-v4.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Wizard v4</span></a></li>

  </ul>

 </div>

</li>

<li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon-bag"></i><span class="kt-menu__link-text">Pricing Tables</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>

 <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

  <ul class="kt-menu__subnav">

   <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Pricing Tables</span></span></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="custom/pricing/pricing-v1.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Pricing Tables v1</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="custom/pricing/pricing-v2.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Pricing Tables v2</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="custom/pricing/pricing-v3.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Pricing Tables v3</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="custom/pricing/pricing-v4.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Pricing Tables v4</span></a></li>

  </ul>

 </div>

</li>

<li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon-piggy-bank"></i><span class="kt-menu__link-text">Invoices</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>

 <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

  <ul class="kt-menu__subnav">

   <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Invoices</span></span></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="custom/invoices/invoice-v1.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Invoice v1</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="custom/invoices/invoice-v2.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Invoice v2</span></a></li>

  </ul>

 </div>

</li>

<li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon-exclamation-2"></i><span class="kt-menu__link-text">FAQ</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>

 <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

  <ul class="kt-menu__subnav">

   <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">FAQ</span></span></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="custom/faq/faq-v1.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">FAQ v1</span></a></li>

  </ul>

 </div>

</li>

<li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon-user"></i><span class="kt-menu__link-text">User Pages</span><span class="kt-menu__link-badge"><span class="kt-badge kt-badge--rounded kt-badge--brand">2</span></span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>

 <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

  <ul class="kt-menu__subnav">

   <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">User Pages</span><span class="kt-menu__link-badge"><span class="kt-badge kt-badge--rounded kt-badge--brand">2</span></span></span></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="custom/user/login-v1.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Login v1</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="custom/user/login-v2.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Login v2</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="custom/user/login-v3.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Login v3</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="custom/user/login-v4.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Login v4</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="custom/user/login-v5.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Login v5</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="custom/user/login-v6.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Login v6</span></a></li>

  </ul>

 </div>

</li>

<li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon flaticon-warning"></i><span class="kt-menu__link-text">Error Pages</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>

 <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>

  <ul class="kt-menu__subnav">

   <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Error Pages</span></span></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="custom/error/error-v1.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Error v1</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="custom/error/error-v2.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Error v2</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="custom/error/error-v3.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Error v3</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="custom/error/error-v4.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Error v4</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="custom/error/error-v5.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Error v5</span></a></li>

   <li class="kt-menu__item " aria-haspopup="true"><a href="custom/error/error-v6.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Error v6</span></a></li>

  </ul>

 </div>

</li> -->

                </ul>

            <?php } ?>

        </div>

    </div>



    <!-- end:: Aside Menu -->

</div>



<!-- end:: Aside -->