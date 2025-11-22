@extends('test.ap')

@section('content')
<div class="hero-wrap" style="background-image: url('images/bg_1.png');">
    <div class="container">
        <div class="row no-gutters slider-text justify-content-start align-items-center">
            <div class="col-lg-7 col-md-6 ftco-animate d-flex align-items-end">
                <div class="text">
                    <p style="color:#3DD0FF;">HIGHLINK</p>
                    <h1 class="mb-4 font-weight-bold">Luxury & Comfort<span>In One Package</span></h1>
                    <p>Welcome aboard and experience luxury and comfort in one package as you<br> travel with our
                        executive coaches, we wish
                        you a happy and comfortable journey with us!</p>

                </div>
            </div>

            <div class="col-lg-5 col-md-6 mt-0 mt-md-5 d-flex">
                 <!-- <form class="request-form ftco-animate" id="busLanding" action="bus_search.html" method="get">
                    <h2>Choose Your Journey</h2>
                    <p>Please choose your preferred journey starting point and destination.</p>
                  <div class="btn-group btn-group-toggle" id="myDIV">
                <label class="btn btn-primary m-0 custom-radio form-check-label btn-color active oneway" id="onwardOption"
                style="border-radius: 5px 5px 0px 0px;color: #0246a5;font-weight:900;padding: 7px;width: 422px;">
                    <input class="form-check-input" type="radio" name="busjourney" id="busonward"
                    checked onclick="showReturnBusJourney();">ONE WAY
                </label>
                <label class="btn btn-primary custom-radio form-check-label btn-color" id="returnOption" style="display: none;border-radius: 5px 5px 0px 0px;padding: 7px;width: 422px;font-weight:900;">
                    <input class="form-check-input" type="radio" name="busjourney" id="busreturn"
                    onclick="showOnwardBusJourney();" > RETURN
                </label>
            </div
                    <div class="form-group">
                        <input type="text" id="fromBusStation" name="fromBusStation"
                            class="form-control destination" value="" autocomplete="off"
                            placeholder="Travel From">
                        <input type="hidden" id="busStartStation" name="busStartStation">
                        <input type="hidden" id="busStartCountry" name="busStartCountry">
                        <input type="hidden" id="busStartStationName" name="busStartStationName">
                        <input type="hidden" id="busJourneyType" value="0" name="busJourneyType">
                        <input type="hidden" id="tabCheck" name="tabCheck" value="1">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="toBusStation" value=""
                            id="toBusStation" autocomplete="off" placeholder="Travel To">
                        <input type="hidden" id="busEndStation" name="busEndStation">
                        <input type="hidden" id="busEndCountry" name="busEndCountry">
                        <input type="hidden" id="busEndStationName" name="busEndStationName">
                    </div>
                    <div class="form-group">
                        <input type="text" id="busOnwardJourneyDate" name="busOnwardJourneyDate"
                            class="form-control" autocomplete="off" placeholder="Departure Date">
                    </div>
                    <div class="form-group" id="bus_returnDateContainer" style="display: none;">
                        <input type="text" class="form-control" id="busReturnJourneyDate"
                            name="busReturnJourneyDate" autocomplete="off" placeholder="Return Date">
                    </div>
                    <div class="form-group">
                        <input type="button" value="Search Buses" class="btn btn-primary1 py-2 px-4"
                            id="searchBus" onclick="validateSearchParams(0)">
                    </div>
                    <div id="busDiagContainer" title="Alert" style="display: none;">
                        <p id="busDiagText"></p>
                    </div>
                </form>>-->
                <div class="btn btn-info g-3 d-flex align-items-center justify-content-center gap-2 px-5 py-3 custom-btn">
                    <i class="fa fa-calendar-check fs-3 py-2 px-3"></i>
                    <span class="fs-3">Booking</span>
                  </div>
            </div>
        </div>
    </div>
</div>    
<style>
    .custom-btn {
      border-radius: 12px; /* Rounded corners */
      text-transform: uppercase; /* Uppercase text for emphasis */
      font-weight: 800; /* Bold text */
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); /* Subtle shadow */
      transition: all 0.3s ease; /* Smooth transitions */
      min-width: 250px; /* Ensure button is wide enough */
      height: 70px; /* Larger height */
    }
  
    .custom-btn:hover {
      transform: translateY(-3px); /* Lift effect on hover */
      box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2); /* Stronger shadow */
      background-color: #31d2f2; /* Slightly lighter info color on hover */
    }
  
    .custom-btn i, .custom-btn span {
      color: #fff; /* Ensure icon and text are white for contrast */
    }
  
    /* Responsive adjustments */
    @media (max-width: 576px) {
      .custom-btn {
        min-width: 200px;
        height: 60px;
        padding: 0.75rem 1.5rem;
      }
      .custom-btn i, .custom-btn span {
        font-size: 1.25rem !important; /* Smaller icon/text on mobile */
      }
    }
  </style>
@endsection