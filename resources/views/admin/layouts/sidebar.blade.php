<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar ">
    <div class="sidebar-img">
        <a class="navbar-brand" href="index-2.html">
            <img alt="..." class="navbar-brand-img main-logo" src="{{ asset('/')}}{{\App\Models\BusinessSetting::where(['type' => 'company_web_logo'])->pluck('value')[0]}}">
        </a>
        <ul class="side-menu">
            <li class="slide">
                <a class="side-menu__item active" href="{{ url('/')}}"><i
                        class="side-menu__icon fe fe-home"></i><span class="side-menu__label">Dashboard</span>
                </a>
            </li>
            <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="#"><i
                        class="side-menu__icon fe fe-book"></i>
                        <span class="side-menu__label">Booking</span><i
                        class="angle fa fa-angle-right"></i></a>
                <ul class="slide-menu">
                    <li>
                        <a href="{{ route('admin.booking.today')}}" class="slide-item">Today Booking</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.booking.index')}}" class="slide-item">Booking List</a>
                    </li>
                    <li class="slide pl-5"><span class="side-menu__label">Schedule Booking</span></li>

                    <li>
                        <a href="{{ route('admin.booking.today-schedule')}}" class="slide-item">Today Schedule Booking</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.booking.schedule')}}" class="slide-item">Schedule Booking</a>
                    </li>

                </ul>
            </li>
            <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="#"><i
                        class="side-menu__icon fe fe-sliders"></i>
                        <span class="side-menu__label">Service</span><i
                        class="angle fa fa-angle-right"></i></a>
                <ul class="slide-menu">
                    <li>
                        <a href="{{ url('admin/service/type')}}" class="slide-item">Service Type</a>
                    </li>
                    <li>
                        <a href="{{ url('admin/service/detail/index') }}" class="slide-item">Services Details</a>
                    </li>

                </ul>
            </li>
            <li class="slide">
                <a class="side-menu__item" href="{{ url('admin/promoCodes/index') }}"><i
                    class="side-menu__icon fe fe-percent"></i>
                    <span class="side-menu__label">Promo Code</span>
                </a>
            </li>
            <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="#"><i
                        class="side-menu__icon fa fa-car"></i>
                        <span class="side-menu__label">Cars</span><i
                        class="angle fa fa-angle-right"></i></a>
                <ul class="slide-menu">
                    <li>
                        <a href="{{ route('admin.state.statelist')}}" class="slide-item">State List</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.car.add-index')}}" class="slide-item">Car List</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.brand.brandlist')}}" class="slide-item">Car Model</a>
                    </li>

                </ul>
            </li>
            <li class="slide p-3"><span class="side-menu__label">User Management</span></li>
            {{-- <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="#"><i
                        class="side-menu__icon fe fe-user"></i>
                        <span class="side-menu__label">Customers</span><i
                        class="angle fa fa-angle-right"></i></a>
                <ul class="slide-menu">
                    <li>
                        <a href="{{ route('admin.customer.index')}}" class="slide-item">Customer List</a>
                    </li>
                </ul>
            </li> --}}
            {{-- <li class="slide">
                <a class="side-menu__item" href="{{ route('admin.customer.index')}}"><i
                    class="side-menu__icon fe fe-user"></i><span class="side-menu__label">Customers</span>
                </a>
            </li> --}}
            <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="#"><i
                        class="side-menu__icon fe fe-user"></i>
                        <span class="side-menu__label">Customer</span><i
                        class="angle fa fa-angle-right"></i></a>
                <ul class="slide-menu">
                    <li>
                        <a href="{{ route('admin.customer.index')}}" class="slide-item">Customer List</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.customer.rides')}}" class="slide-item">Customer Ride</a>
                    </li>

                </ul>
            </li>
            {{-- <li class="slide">
                <a class="side-menu__item" href="#"><i
                    class="side-menu__icon fe fe-truck"></i><span class="side-menu__label">Pickup Driver.</span>
                </a>
            </li> --}}
            {{-- <li class="slide">
                <a class="side-menu__item" href="{{ route('admin.driver.index')}}"><i
                    class="side-menu__icon fe fe-truck"></i><span class="side-menu__label">Driver</span>
                </a>
            </li> --}}
            <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="#"><i
                        class="side-menu__icon fe fe-grid"></i>
                        <span class="side-menu__label">Drivers</span><i
                        class="angle fa fa-angle-right"></i></a>
                <ul class="slide-menu">
                    <li>
                        <a href="{{ route('admin.driver.pending')}}" class="slide-item">Pending Request</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.driver.index')}}" class="slide-item">Driver List</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.driver.rides')}}" class="slide-item">Driver Rides</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.driver.wallet')}}" class="slide-item">Wallets</a>
                    </li>
                    {{-- <li>
                        <a href="{{ route('admin.driver.pendingwithdraw')}}" class="slide-item">With Draw</a>
                    </li> --}}

                </ul>
            </li>

            {{-- <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="#"><i
                        class="side-menu__icon fe fe-grid"></i>
                        <span class="side-menu__label">Drivers</span><i
                        class="angle fa fa-angle-right"></i></a>
                <ul class="slide-menu">
                    <li>
                        <a href="#" class="slide-item">Customer List</a>
                    </li>
                    <li>
                        <a href="#" class="slide-item">Customer Reviews</a>
                    </li>
                    <li>
                        <a href="#" class="slide-item">Wallets</a>
                    </li>

                </ul>
            </li> --}}


            <li class="slide p-3"><span class="side-menu__label">System Settings</span></li>

            <li class="slide">
                <a class="side-menu__item" href="{{url('business-settings/companyInfo')}}">
                    <i class="side-menu__icon fe fe-settings"></i>
                    <span class="side-menu__label">Business Setup</span>
                </a>
            </li>


            {{-- <li class="slide">
                <a class="side-menu__item" href="{{ route('admin.rate.rateType')}}"><i
                        class="side-menu__icon fe fe-credit-card"></i><span class="side-menu__label">Service Type</span>
                </a>
            </li> --}}
            {{-- <a class="side-menu__item" href="#"><i
                class="side-menu__icon fa fa-key"></i><span class="side-menu__label">3rd Party</span>
            </a> --}}
            {{-- <a class="side-menu__item" href="#"><i
                class="side-menu__icon fa fa-key"></i><span class="side-menu__label">System Setup</span>
            </a> --}}




        </ul>
    </div>
</aside>
<!-- Sidebar menu-->
