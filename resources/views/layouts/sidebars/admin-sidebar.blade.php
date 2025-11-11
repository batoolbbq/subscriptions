<div class="container-fluid">

    <div class="row">

        <!-- Left Sidebar start-->
        <div class="side-menu-fixed">
            <div class="scrollbar side-menu-bg"
                style="background: linear-gradient(180deg, #FFF7EE, #FCE8D6); color: black !important;">
                <ul class="nav navbar-nav side-menu" id="sidebarnav"
                    style="background: linear-gradient(180deg, #FFF7EE, #FCE8D6); color: black !important;">
                    <!-- employees -->







                    <!-- menu item Dashboard-->
                    @can('dashboard')
                        <li>
                            <a href=""><i class="ti-home"></i><span
                                    class="right-nav-text">{{ __('لوحة التحكم') }}</span></a>
                        </li>
                    @endcan


                    <li>
                        <a href="{{ route('home') }}"><i class="fas fa-home"></i><span
                                class="right-nav-text">{{ __('الرئيسية') }}</span></a>
                    </li>


                    <!-- <li class="mt-10 mb-10 text-muted pl-4 font-medium menu-title"> {{ __('Components') }} </li> -->

                    @can('permissions.index')
                        <li>
                            <a href="{{ route('permissions.index') }}"><i class="fas fa-key"></i><span
                                    class="right-nav-text">{{ __(' الصلاحيات') }}</span></a>
                        </li>
                    @endcan

                    @can('roles.index')
                        <li>
                            <a href="{{ route('roles.index') }}"><i class="fas fa-users-cog"></i><span
                                    class="right-nav-text">{{ __('  الادوار') }}</span></a>
                        </li>
                    @endcan

                    @can('users.index')
                        <li>
                            <a href="{{ route('users.index') }}"><i class="fas fa-user-friends"></i><span
                                    class="right-nav-text">{{ __('  المستخدمين') }}</span></a>
                        </li>
                    @endcan

                    @can('insuranceAgents.index')
                        <li>
                            <a href="{{ route('insuranceAgents.index') }}"><i class="fas fa-user-shield"></i><span
                                    class="right-nav-text">{{ __('  وكلاء التأمين') }}</span></a>
                        </li>
                    @endcan

                    @can('beneficiariescategory.index')
                        <li>
                            <a href="{{ route('beneficiariescategory.index') }}"><i class="fas fa-layer-group"></i><span
                                    class="right-nav-text">{{ __('  الفئات ') }}</span></a>
                        </li>
                    @endcan

                    @can('beneficiaries-sup-categories.index')
                        <li>
                            <a href="{{ route('beneficiaries-sup-categories.index') }}"><i class="fas fa-sitemap"></i><span
                                    class="right-nav-text">{{ __('الفئات الفرعية ') }}</span></a>
                        </li>
                    @endcan

                    @can('subscriptions.index')
                        <li>
                            <a href="{{ route('subscriptions.index') }}"><i class="fas fa-file-invoice-dollar"></i><span
                                    class="right-nav-text">{{ __('  الاشتراكات ') }}</span></a>
                        </li>
                    @endcan

                    @can('institucions.create')
                        <li>
                            <a href="{{ route('institucions.create') }}"><i class="fas fa-building"></i><span
                                    class="right-nav-text">{{ __(' تسجيل جهة عمل') }}</span></a>
                        </li>
                    @endcan

                    <li>
                        <a href="{{ route('register-customerr') }}"><i class="fas fa-user-plus"></i><span
                                class="right-nav-text">{{ __('  تسجيل مشترك ') }}</span></a>
                    </li>

                    @can('institucions.index')
                        <li>
                            <a href="{{ route('institucions.index') }}"><i class="fas fa-building"></i><span
                                    class="right-nav-text">{{ __(' جهات العمل ') }}</span></a>
                        </li>
                    @endcan

                    @can('agents.performance.index')
                        <li>
                            <a href="{{ route('agents.performance.index') }}"><i class="fas fa-chart-line"></i><span
                                    class="right-nav-text">{{ __('معدل اداء الوكلاء') }}</span></a>
                        </li>
                    @endcan

                    <li>
                        <a href="{{ route('customers.search.form') }}"><i class="fas fa-search"></i><span
                                class="right-nav-text">{{ __('البحث عن مشترك / منتفع') }}</span></a>
                    </li>

                    <li>
                        <a href="{{ route('customer.searchEditForm') }}"><i class="fas fa-user-edit"></i><span
                                class="right-nav-text">{{ __(' تعديل بيانات مشترك/منتفع ') }}</span></a>
                    </li>

                    {{-- <li>
                        <a href="{{ route('agents.performance.index') }}"><i class="fas fa-exchange-alt"></i><span
                                class="right-nav-text">{{ __(' التحويل من فئة لي فئة ') }}</span></a>
                    </li> --}}


                    <li>
                        <a href="{{ route('customers.lookup') }}"><i class="fas fa-exchange-alt"></i><span
                                class="right-nav-text">{{ __('بدل فاقد ') }}</span></a>
                    </li>

                         {{-- <li>
                        <a href="{{ route('customers.renewal') }}"><i class="fas fa-exchange-alt"></i><span
                                class="right-nav-text">{{ __('تجديد بطاقة ') }}</span></a>
                    </li> --}}



                    <li>
                        <a href="{{ route('cards/index') }}">
                            <i class="fas fa-id-card"></i>
                            <span class="right-nav-text">{{ __('تصوير مشترك / منتفع') }}</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('renew.page') }}">
                            <i class="fas fa-id-card"></i>
                            <span class="right-nav-text">{{ __(' تجديد بطاقة ') }}</span>
                        </a>
                    </li>

                    @can('workplace_codes.create')
                        <li>
                            <a href="{{ route('workplace_codes.create') }}">
                                <i class="fas fa-layer-group"></i>
                                <span class="right-nav-text">{{ __('ترميزات رئيسية') }}</span>
                            </a>
                        </li>
                    @endcan

                    @can('workplace_codes.create_child')
                        <li>
                            <a href="{{ route('workplace_codes.create_child') }}">
                                <i class="fas fa-sitemap"></i>
                                <span class="right-nav-text">{{ __('ترميزات فرعية') }}</span>
                            </a>
                        </li>
                    @endcan





                    <!-- الاقسام -->

                    <!-- 'record-damage -->
                    @can('record-damage')
                        <li>
                            <a href="{{ route('record-damage') }}"><i class="fa fa-comment"></i><span
                                    class="right-nav-text">{{ __('  المخزون العام ') }}</span> </a>
                        </li>
                    @endcan
                    <!-- municipleStock -->
                    @can('municipleStock')
                        <li>
                            <a href="{{ route('municipleStock') }}"><i class="fa fa-comment"></i><span
                                    class="right-nav-text">{{ __('  مخزون البلدية') }}</span> </a>
                        </li>
                    @endcan
                    <!-- showRequests -->
                    @can('showRequests')
                        <li>
                            <a href="{{ route('showRequests') }}"><i class="fa fa-comment"></i><span
                                    class="right-nav-text">{{ __('  الطلبات') }}</span> </a>
                        </li>
                    @endcan
                    @can('showRequestsforCenter')
                        <li>
                            <a href="{{ route('showRequestsforCenter') }}"><i class="fa fa-comment"></i><span
                                    class="right-nav-text">{{ __('  طلبات الامداد') }}</span> </a>
                        </li>
                    @endcan
                    @can('vaccinated')
                        <li>
                            <a href="{{ route('vaccinated') }}"><i class="fa fa-user"></i><span
                                    class="right-nav-text">{{ __('  المتطعمين') }}</span> </a>
                        </li>
                    @endcan
                    <!-- contacts -->
                    @can('contacts')
                        <li>
                            <a href="{{ route('contacts') }}"><i class="fa fa-comment"></i><span
                                    class="right-nav-text">{{ __('  التواصل والشكاوي') }}</span> </a>
                        </li>
                    @endcan

                    @can('vaccines')
                        <li>
                            <a href="{{ route('vaccines') }}"><i class="ti-briefcase"></i><span
                                    class="right-nav-text">{{ __('ادارة اللقاحات') }}</span></a>
                        </li>
                    @endcan

                    @can('transferStock')
                        <li>
                            <a href="{{ route('transferStock') }}"><i class="fa fa-comment"></i><span
                                    class="right-nav-text">{{ __('  الامداد') }}</span> </a>
                        </li>
                    @endcan

                    @can('admin.vaccines.transferFromMunicipalToCenter')
                        <li>
                            <a href="{{ route('admin.vaccines.transferFromMunicipalToCenter') }}"><i
                                    class="fa fa-comment"></i><span class="right-nav-text">{{ __('  الامداد') }}</span>
                            </a>
                        </li>
                    @endcan
                    <!-- center.vaccines.pendingTransfers -->
                    @can('pendingTransfers')
                        <li>
                            <a href="{{ route('center.vaccines.pendingTransfers') }}"><i class="fa fa-comment"></i><span
                                    class="right-nav-text">{{ __('  تأكيد استلام الامداد') }}</span> </a>
                        </li>
                    @endcan




                    <!-- municiple.index -->
                    @can('municiple.index')
                        <li>
                            <a href="{{ route('municiple.index') }}"><i class="fa fa-building "></i><span
                                    class="right-nav-text">{{ __(' البلديات') }}</span></a>
                        </li>
                    @endcan

                    <!-- <li class="mt-10 mb-10 text-muted pl-4 font-medium menu-title"> {{ __('Users') }} </li> -->

                    @can('centers')
                        <li>
                            <a href="{{ route('centers') }}"><i class="ti-user"></i><span
                                    class="right-nav-text">{{ __(' المراكز الصحية') }}</span></a>
                        </li>
                    @endcan

                    @can('parents')
                        <li>
                            <a href="{{ route('parents') }}"><i class="ti-user"></i><span
                                    class="right-nav-text">{{ __(' اولياء الامور') }}</span></a>
                        </li>
                    @endcan
                </ul>
                <style>
                    /* تمييز العنصر النشط */
                    .side-menu li.active-menu>a {
                        background-color: #ffb066;
                        color: #fff !important;
                        border-radius: 8px;
                    }

                    .side-menu li.active-menu>a i {
                        color: #fff;
                        background-color: rgba(255, 255, 255, .18);
                        border-radius: 6px;
                        padding: 5px;
                    }

                    /* هوفر لطيف لبقية العناصر */
                    .side-menu li:not(.active-menu)>a:hover {
                        background: rgba(0, 0, 0, .06);
                    }

                    /* العناصر غير النشطة */
                    .side-menu li:not(.active-menu)>a {
                        color: #444 !important;
                        /* لون أغمق للنص */
                    }

                    .side-menu li:not(.active-menu)>a i {
                        color: #444;
                        /* لون أغمق للأيقونة */
                        background: transparent;
                    }

                    /* العنصر النشط */
                    .side-menu li.active-menu>a {
                        background-color: #F58220;
                        color: #fff !important;
                    }

                    .side-menu li.active-menu>a i {
                        color: #fff;
                        background-color: rgba(255, 255, 255, 0.18);
                        border-radius: 6px;
                        padding: 5px;
                    }
                </style>
                <script>
                    (function() {
                        var current = window.location.pathname.replace(/\/+$/, ""); // بدون السلاش الأخير
                        var links = document.querySelectorAll('#sidebarnav > li > a');

                        links.forEach(function(a) {
                            try {
                                var aPath = new URL(a.getAttribute('href'), window.location.origin)
                                    .pathname.replace(/\/+$/, "");
                                // مطابق تمامًا أو مسار أب (يدعم صفحات فرعية)
                                if (aPath && (aPath === current || (aPath !== "/" && current.startsWith(aPath + "/")))) {
                                    a.parentElement.classList.add('active-menu');
                                }
                            } catch (e) {
                                // لو href = "javascript:void(0)" أو مشابه، نتجاهل
                            }
                        });
                    })();
                </script>


                </li>
                </ul>
            </div>
        </div>



        <!-- Left Sidebar End-->

        <!--=================================
