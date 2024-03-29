@extends('admin.layouts.app');

@section('content')
    
<section class="section">
  @include('admin.components.validationMessage')

  <div class="row ">
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
      <div class="card">
        <div class="card-statistic-4">
          <div class="align-items-center justify-content-between">
            <div class="row ">
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                <div class="card-content">
                  <h5 class="font-15">Commandes</h5>
                  <h2 class="mb-3 font-18">258</h2>
                  <p class="mb-0"><span class="col-green">10%</span> Increase</p>
                </div>
              </div>
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                <div class="banner-img">
                  <img src="assets/img/banner/1.png" alt="">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
      <div class="card">
        <div class="card-statistic-4">
          <div class="align-items-center justify-content-between">
            <div class="row ">
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                <div class="card-content">
                  <h5 class="font-15"> Produits</h5>
                  <h2 class="mb-3 font-18">1,287</h2>
                  <p class="mb-0"><span class="col-orange">09%</span> Decrease</p>
                </div>
              </div>
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                <div class="banner-img">
                  <img src="assets/img/banner/2.png" alt="">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
      <div class="card">
        <div class="card-statistic-4">
          <div class="align-items-center justify-content-between">
            <div class="row ">
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                <div class="card-content">
                  <h5 class="font-15">Clients</h5>
                  <h2 class="mb-3 font-18">128</h2>
                  <p class="mb-0"><span class="col-green">18%</span>
                    Increase</p>
                </div>
              </div>
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                <div class="banner-img">
                  <img src="assets/img/banner/3.png" alt="">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
      <div class="card">
        <div class="card-statistic-4">
          <div class="align-items-center justify-content-between">
            <div class="row ">
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                <div class="card-content">
                  <h5 class="font-15">Visites</h5>
                  <h2 class="mb-3 font-18">$48,697</h2>
                  <p class="mb-0"><span class="col-green">42%</span> Increase</p>
                </div>
              </div>
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                <div class="banner-img">
                  <img src="assets/img/banner/4.png" alt="">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  {{-- <div class="row">
    <div class="col-12 col-sm-12 col-lg-12">
      <div class="card ">
        <div class="card-header">
          <h4>Revenue chart</h4>
          <div class="card-header-action">
            <div class="dropdown">
              <a href="#" data-toggle="dropdown" class="btn btn-warning dropdown-toggle">Options</a>
              <div class="dropdown-menu">
                <a href="#" class="dropdown-item has-icon"><i class="fas fa-eye"></i> View</a>
                <a href="#" class="dropdown-item has-icon"><i class="far fa-edit"></i> Edit</a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item has-icon text-danger"><i class="far fa-trash-alt"></i>
                  Delete</a>
              </div>
            </div>
            <a href="#" class="btn btn-primary">View All</a>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-9">
              <div id="chart1"></div>
              <div class="row mb-0">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                  <div class="list-inline text-center">
                    <div class="list-inline-item p-r-30"><i data-feather="arrow-up-circle"
                        class="col-green"></i>
                      <h5 class="m-b-0">$675</h5>
                      <p class="text-muted font-14 m-b-0">Weekly Earnings</p>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                  <div class="list-inline text-center">
                    <div class="list-inline-item p-r-30"><i data-feather="arrow-down-circle"
                        class="col-orange"></i>
                      <h5 class="m-b-0">$1,587</h5>
                      <p class="text-muted font-14 m-b-0">Monthly Earnings</p>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                  <div class="list-inline text-center">
                    <div class="list-inline-item p-r-30"><i data-feather="arrow-up-circle"
                        class="col-green"></i>
                      <h5 class="mb-0 m-b-0">$45,965</h5>
                      <p class="text-muted font-14 m-b-0">Yearly Earnings</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="row mt-5">
                <div class="col-7 col-xl-7 mb-3">Total customers</div>
                <div class="col-5 col-xl-5 mb-3">
                  <span class="text-big">8,257</span>
                  <sup class="col-green">+09%</sup>
                </div>
                <div class="col-7 col-xl-7 mb-3">Total Income</div>
                <div class="col-5 col-xl-5 mb-3">
                  <span class="text-big">$9,857</span>
                  <sup class="text-danger">-18%</sup>
                </div>
                <div class="col-7 col-xl-7 mb-3">Project completed</div>
                <div class="col-5 col-xl-5 mb-3">
                  <span class="text-big">28</span>
                  <sup class="col-green">+16%</sup>
                </div>
                <div class="col-7 col-xl-7 mb-3">Total expense</div>
                <div class="col-5 col-xl-5 mb-3">
                  <span class="text-big">$6,287</span>
                  <sup class="col-green">+09%</sup>
                </div>
                <div class="col-7 col-xl-7 mb-3">New Customers</div>
                <div class="col-5 col-xl-5 mb-3">
                  <span class="text-big">684</span>
                  <sup class="col-green">+22%</sup>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div> --}}

<!-- ========== Start graphique statistic ========== -->

  {{-- <div class="row">
    <div class="col-12 col-sm-12 col-lg-4">
      <div class="card">
        <div class="card-header">
          <h4>Meilleurs clients</h4>
        </div>
        <div class="card-body">
          <div id="chart4" class="chartsh"></div>
        </div>
      </div>
    </div>
    <div class="col-12 col-sm-12 col-lg-4">
      <div class="card">
        <div class="card-header">
          <h4>Meilleurs categories</h4>
        </div>
        <div class="card-body">
          <div class="summary">
            <div class="summary-chart active" data-tab-group="summary-tab" id="summary-chart">
              <div id="chart3" class="chartsh"></div>
            </div>
            <div data-tab-group="summary-tab" id="summary-text">
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-sm-12 col-lg-4">
      <div class="card">
        <div class="card-header">
          <h4>produits les plus commandés</h4>
        </div>
        <div class="card-body">
          <div id="chart2" class="chartsh"></div>
        </div>
      </div>
    </div>
  </div> --}}

<!-- ========== End graphique statistic ========== -->






  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h4>Dernières Commandes En attente</h4>
        </div>
        <div class="card-body">
          <div class="table-responsive">
              <table class="table table-striped table-hover" id="tableExport">
                  <thead>
                      <tr>
                          <th class="text-center">
                              #
                          </th>
                          <th>code</th>
                          <th>client</th>
                          <th>Livraison</th>
                          <th>Total</th>
                          <th>date</th>
                          <th>Action</th>
                      </tr>
                  </thead>
                  <tbody>
                      @foreach ($orders_attente as $key => $item)
                          <tr>
                              <td>{{ ++$key }} </td>
                              <td><span style="font-weight:bold">{{ $item['code'] }}</span>
                                  <br> <span class="{{$item['status']=='attente' ? 'bg-primary': ($item['status']=='livrée' ? 'bg-success' : ($item['status']=='confirmée' ? 'bg-blue' :  ($item['status']=='annulée' ? 'bg-danger' : '')))}} text-white p-1 px-3">{{ $item['status'] }}
                                  </span>
                              </td>
                              <td>{{ $item['user']['name'] }} </td>
                              <td>{{ $item['delivery_name'] }} - {{ $item['delivery_price'] }} </td>
                              <td>{{ $item['total'] }} </td>
                              <td>
                                  {{ \Carbon\Carbon::parse($item['created_at'])->diffForHumans() }}
                                  <br>
                                  {{ \Carbon\Carbon::parse($item['created_at'])->isoFormat('dddd D MMMM YYYY') }}

                              </td>

                              <td>
                                  <div class="dropdown">
                                      <a href="#" data-toggle="dropdown"
                                          class="btn btn-warning dropdown-toggle">Options</a>
                                      <div class="dropdown-menu">
                                          <a href="{{ route('order.show', $item['id']) }}"
                                              class="dropdown-item has-icon"><i class="fas fa-eye"></i>
                                              Detail</a>

                                              @if ($item['status']!='livrée')
                                              <a href="/admin/order/changeState?cs=confirmée && id={{$item['id']}}"
                                              class="dropdown-item has-icon"><i
                                                  class="fas fa-check"></i>
                                              Confirmée</a>
                                              <a href="/admin/order/changeState?cs=livrée && id={{$item['id']}}"
                                              class="dropdown-item has-icon"><i
                                                  class="fas fa-shipping-fast"></i>
                                              Livrée</a>
                                          <a href="/admin/order/changeState?cs=attente && id={{$item['id']}}"
                                              class="dropdown-item has-icon"><i
                                                  class="fas fa-arrow-down"></i>
                                              Attente</a>

                                          <a href="/admin/order/changeState?cs=annulée && id={{$item['id']}}" role="button" data-id="{{ $item['id'] }}"
                                              class="dropdown-item has-icon text-danger delete"><i
                                                  data-feather="x-circle"></i> Annuler</a>

                                              @endif

                                             
                                      </div>
                                  </div>
                              </td>


                            
                          </tr>
                      @endforeach

                  </tbody>
              </table>
          </div>
      </div>
      </div>
    </div>
  </div>
  {{-- <div class="row">
    <div class="col-md-6 col-lg-12 col-xl-6">
      <!-- Support tickets -->
      <div class="card">
        <div class="card-header">
          <h4>Support Ticket</h4>
          <form class="card-header-form">
            <input type="text" name="search" class="form-control" placeholder="Search">
          </form>
        </div>
        <div class="card-body">
          <div class="support-ticket media pb-1 mb-3">
            <img src="assets/img/users/user-1.png" class="user-img mr-2" alt="">
            <div class="media-body ml-3">
              <div class="badge badge-pill badge-success mb-1 float-right">Feature</div>
              <span class="font-weight-bold">#89754</span>
              <a href="javascript:void(0)">Please add advance table</a>
              <p class="my-1">Hi, can you please add new table for advan...</p>
              <small class="text-muted">Created by <span class="font-weight-bold font-13">John
                  Deo</span>
                &nbsp;&nbsp; - 1 day ago</small>
            </div>
          </div>
          <div class="support-ticket media pb-1 mb-3">
            <img src="assets/img/users/user-2.png" class="user-img mr-2" alt="">
            <div class="media-body ml-3">
              <div class="badge badge-pill badge-warning mb-1 float-right">Bug</div>
              <span class="font-weight-bold">#57854</span>
              <a href="javascript:void(0)">Select item not working</a>
              <p class="my-1">please check select item in advance form not work...</p>
              <small class="text-muted">Created by <span class="font-weight-bold font-13">Sarah
                  Smith</span>
                &nbsp;&nbsp; - 2 day ago</small>
            </div>
          </div>
          <div class="support-ticket media pb-1 mb-3">
            <img src="assets/img/users/user-3.png" class="user-img mr-2" alt="">
            <div class="media-body ml-3">
              <div class="badge badge-pill badge-primary mb-1 float-right">Query</div>
              <span class="font-weight-bold">#85784</span>
              <a href="javascript:void(0)">Are you provide template in Angular?</a>
              <p class="my-1">can you provide template in latest angular 8.</p>
              <small class="text-muted">Created by <span class="font-weight-bold font-13">Ashton Cox</span>
                &nbsp;&nbsp; -2 day ago</small>
            </div>
          </div>
          <div class="support-ticket media pb-1 mb-3">
            <img src="assets/img/users/user-6.png" class="user-img mr-2" alt="">
            <div class="media-body ml-3">
              <div class="badge badge-pill badge-info mb-1 float-right">Enhancement</div>
              <span class="font-weight-bold">#25874</span>
              <a href="javascript:void(0)">About template page load speed</a>
              <p class="my-1">Hi, John, can you work on increase page speed of template...</p>
              <small class="text-muted">Created by <span class="font-weight-bold font-13">Hasan
                  Basri</span>
                &nbsp;&nbsp; -3 day ago</small>
            </div>
          </div>
        </div>
        <a href="javascript:void(0)" class="card-footer card-link text-center small ">View
          All</a>
      </div>
      <!-- Support tickets -->
    </div>
    <div class="col-md-6 col-lg-12 col-xl-6">
      <div class="card">
        <div class="card-header">
          <h4>Projects Payments</h4>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover mb-0">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Client Name</th>
                  <th>Date</th>
                  <th>Payment Method</th>
                  <th>Amount</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>1</td>
                  <td>John Doe </td>
                  <td>11-08-2018</td>
                  <td>NEFT</td>
                  <td>$258</td>
                </tr>
                <tr>
                  <td>2</td>
                  <td>Cara Stevens
                  </td>
                  <td>15-07-2018</td>
                  <td>PayPal</td>
                  <td>$125</td>
                </tr>
                <tr>
                  <td>3</td>
                  <td>
                    Airi Satou
                  </td>
                  <td>25-08-2018</td>
                  <td>RTGS</td>
                  <td>$287</td>
                </tr>
                <tr>
                  <td>4</td>
                  <td>
                    Angelica Ramos
                  </td>
                  <td>01-05-2018</td>
                  <td>CASH</td>
                  <td>$170</td>
                </tr>
                <tr>
                  <td>5</td>
                  <td>
                    Ashton Cox
                  </td>
                  <td>18-04-2018</td>
                  <td>NEFT</td>
                  <td>$970</td>
                </tr>
                <tr>
                  <td>6</td>
                  <td>
                    John Deo
                  </td>
                  <td>22-11-2018</td>
                  <td>PayPal</td>
                  <td>$854</td>
                </tr>
                <tr>
                  <td>7</td>
                  <td>
                    Hasan Basri
                  </td>
                  <td>07-09-2018</td>
                  <td>Cash</td>
                  <td>$128</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div> --}}
</section>
<div class="settingSidebar">
  <a href="javascript:void(0)" class="settingPanelToggle"> <i class="fa fa-spin fa-cog"></i>
  </a>
  <div class="settingSidebar-body ps-container ps-theme-default">
    <div class=" fade show active">
      <div class="setting-panel-header">Setting Panel
      </div>
      <div class="p-15 border-bottom">
        <h6 class="font-medium m-b-10">Select Layout</h6>
        <div class="selectgroup layout-color w-50">
          <label class="selectgroup-item">
            <input type="radio" name="value" value="1" class="selectgroup-input-radio select-layout" checked>
            <span class="selectgroup-button">Light</span>
          </label>
          <label class="selectgroup-item">
            <input type="radio" name="value" value="2" class="selectgroup-input-radio select-layout">
            <span class="selectgroup-button">Dark</span>
          </label>
        </div>
      </div>
      <div class="p-15 border-bottom">
        <h6 class="font-medium m-b-10">Sidebar Color</h6>
        <div class="selectgroup selectgroup-pills sidebar-color">
          <label class="selectgroup-item">
            <input type="radio" name="icon-input" value="1" class="selectgroup-input select-sidebar">
            <span class="selectgroup-button selectgroup-button-icon" data-toggle="tooltip"
              data-original-title="Light Sidebar"><i class="fas fa-sun"></i></span>
          </label>
          <label class="selectgroup-item">
            <input type="radio" name="icon-input" value="2" class="selectgroup-input select-sidebar" checked>
            <span class="selectgroup-button selectgroup-button-icon" data-toggle="tooltip"
              data-original-title="Dark Sidebar"><i class="fas fa-moon"></i></span>
          </label>
        </div>
      </div>
      <div class="p-15 border-bottom">
        <h6 class="font-medium m-b-10">Color Theme</h6>
        <div class="theme-setting-options">
          <ul class="choose-theme list-unstyled mb-0">
            <li title="white" class="active">
              <div class="white"></div>
            </li>
            <li title="cyan">
              <div class="cyan"></div>
            </li>
            <li title="black">
              <div class="black"></div>
            </li>
            <li title="purple">
              <div class="purple"></div>
            </li>
            <li title="orange">
              <div class="orange"></div>
            </li>
            <li title="green">
              <div class="green"></div>
            </li>
            <li title="red">
              <div class="red"></div>
            </li>
          </ul>
        </div>
      </div>
      <div class="p-15 border-bottom">
        <div class="theme-setting-options">
          <label class="m-b-0">
            <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input"
              id="mini_sidebar_setting">
            <span class="custom-switch-indicator"></span>
            <span class="control-label p-l-10">Mini Sidebar</span>
          </label>
        </div>
      </div>
      <div class="p-15 border-bottom">
        <div class="theme-setting-options">
          <label class="m-b-0">
            <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input"
              id="sticky_header_setting">
            <span class="custom-switch-indicator"></span>
            <span class="control-label p-l-10">Sticky Header</span>
          </label>
        </div>
      </div>
      <div class="mt-4 mb-4 p-3 align-center rt-sidebar-last-ele">
        <a href="#" class="btn btn-icon icon-left btn-primary btn-restore-theme">
          <i class="fas fa-undo"></i> Restore Default
        </a>
      </div>
    </div>
  </div>
</div>

@endsection


     
      
    

