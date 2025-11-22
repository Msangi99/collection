@extends('test.ap')

@section('content')

@include('test.sach')
<section class="ftco-section services-section" style="background:#F4F4F4;margin-top: 20px;">
    <div class="container">
            <div class="" id="busContainer" style="">
                <div id="allBusListContainer">
                    <div id="busCountContainer" class="col-md-12">
                        <div id="busCountText" class="font-weight-bold mb-2"></div>
                    </div>
                    <div id="onwardBusSearchContainer" class="">
                        <div class="mb-3 pt-2 filters operator_0 bustype_0 usbcharging tv ac refreshments toilet operator_all bustype_all time_all currency_0_all time_2 currency_0_1 boardingPoint_all droppingPoint_all boardingPoint_0 boardingPoint_1 boardingPoint_2 boardingPoint_3 boardingPoint_4 boardingPoint_5 boardingPoint_6 boardingPoint_7 boardingPoint_8 boardingPoint_9 boardingPoint_10 boardingPoint_11 boardingPoint_12 boardingPoint_13 boardingPoint_14 boardingPoint_15 boardingPoint_16 boardingPoint_17 boardingPoint_18 boardingPoint_19 droppingPoint_0"
                            style="border-radius: 5px; background: rgb(255, 255, 255); box-shadow: rgb(89, 89, 89) 0px 2px 5px -2px;"
                            id="busSection_0_0">
                            <div class="col-md-12 col-12">
                                <img src="images/srbus.png" width="30px;" style="padding-right:5px;"
                                    class="img-fluid">
                                <span style="font-weight:600;color:#000;" class="pr-3" id="busName_0_0">BM COACH DSM
                                    ARS MCHANA</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 10 10"
                                    fill="none">
                                    <circle cx="4.78114" cy="4.78114" r="4.78114" fill="#38B548"></circle>
                                </svg>
                                Plate No. - <label class="mb-0" id="busPlateNumber_0_0">T 583 EBL</label>
                                <p id="busType_0_0">2x2 Luxury| <span style="color:#d55b4c;" id="busVia_0_0"> VIA
                                        BAGAMOYO</span></p>
                            </div>
                            <div class="col-md-12">
                                <div class="row" style="color:#000;">
                                    <div class="col-lg-2 col">
                                        <span style="font-weight:bold;" id="deptTime_0_0">
                                            14:00 PM</span>
                                    </div>
                                    <div class="col-lg-3 col">
                                        <span class="p-2" id="journeyDurationText_0_0">
                                            <img src="images/timebar1.png" style="width: 40px;"><span
                                                style="color:#333333"> 10 Hrs 00 Min </span> <img
                                                src="images/timebar2.png" style="width: 40px;"></span>
                                    </div>
                                    <div class="col-lg-2 col">
                                        <input type="hidden" value="10:00" id="journeyDuration_0_0">
                                        <span style="font-weight:bold;" id="arrTime_0_0">00:00 AM</span>
                                    </div>

                                    <div class="col-lg-2 main-fair">
                                        <div class="col-md-12 pt-2 pb-2"
                                            style="border-radius: 10px;background: #FAFAFA;" align="center">
                                            <b style="color: #5F5F5F;">TSH. 50,000</b>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-12 main-button" align="right">
                                        <div class="button1_wrapper">
                                            <img src="images/seatavail.png">
                                            <small class="mt-1" style="color:#464646">1 Seats Left</small>
                                            <div class="pt-2 currencyAvailable_0_0">
                                                <button type="button" class="btn btn-warning btn-lg"
                                                    id="selectSeats_0_0"
                                                    style="border-radius: 5px;border:none;background: #ED1C24;color:#fff;"
                                                    onclick="checkCurrency(0, 0, 0)">Select Seats</button>
                                                <input type="hidden" id="sub_id_0_0" value="1462971">
                                                <input type="hidden" id="tdi_id_0_0" value="304068786">
                                                <input type="hidden" id="lb_id_0_0" value="9919">
                                                <input type="hidden" id="responsekey_0_0"
                                                    value="1d33c48f1c5fdc26755d957439ccef95">
                                                <input type="hidden" id="pbi_id_0_0" value="1328">
                                                <input type="hidden" id="asi_id_0_0" value="301045677">
                                                <input type="hidden" id="cur_count_0_0" value="1">
                                                <input type="hidden" id="cur_id_0_0" value="1">
                                                <input type="hidden" id="precur_id_0_0" value="">
                                                <input type="hidden" id="sleeper_0_0" value="0">
                                                <input type="hidden" id="doubledecker_0_0" value="0">
                                                <input type="hidden" id="deptDate_0_0" value="2025-04-18">
                                                <input type="hidden" id="arrDate_0_0" value="2025-04-19">
                                            </div>
                                            <div class="currencyNotAvailable_0_0 text-center"
                                                style="display: none; color: red;">
                                                Currency Not Supported
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row pb-3">
                                    <div class="col-md-1 col-3">
                                        <div
                                            style="background-color: #ED1C24;border-radius: 5px;padding: 0px 0px 0px 10px;color: #fff;">
                                            <i class="fa fa-star" style="font-size: 10px;"></i> <span
                                                style="font-size: 13px;">4.5</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-9">
                                        <span style="color:#696969;cursor:pointer;"
                                            onclick="myFunction(0)">Amenities</span>
                                        <span
                                            style="border-right: 1px solid #696969;margin-left: 20px;margin-right: 20px;"></span>
                                        <span style="color:#696969;">Booking Policy</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div id="amenities_div_0" class="amenities_div" style="display:none;">
                                    <div class="row p-3" style="background: #FAFAFA;">
                                        <div class="col-md-3 mb-2"><span><img src="../images/usb.png" alt="usb"
                                                    class="img-fluid">&nbsp;&nbsp; USB Charging</span></div>
                                        <div class="col-md-3 mb-2"><span><img src="../images/tv.png" alt="tv"
                                                    class="img-fluid">&nbsp;&nbsp; TV</span></div>
                                        <div class="col-md-3 mb-2"><span><img src="../images/ac.svg" alt="ac"
                                                    class="img-fluid" width="25px">&nbsp;&nbsp; AC</span></div>
                                        <div class="col-md-3 mb-2"><span><img src="../images/Refreshments.svg"
                                                    alt="Refreshments" class="img-fluid" width="25px">&nbsp;&nbsp;
                                                Refreshments</span></div>
                                        <div class="col-md-3 mb-2"><span><img src="../images/toilet.svg"
                                                    alt="toilet" class="img-fluid" width="25px">&nbsp;&nbsp;
                                                Toilet</span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="seatMapContainer_0" id="seatMap_0_0" style="display: block;"></div>
                        </div>
                    </div>
                    <div id="returnBusSearchContainer" class="col-md-12" style="display: none;"></div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection