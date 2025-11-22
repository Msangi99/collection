<!DOCTYPE html>
<html>

<head>
    <title>Bus Ticket</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 10px;
            line-height: 1.2;
            margin: 0;
            padding: 5mm;
            width: 80mm;
        }

        .ticket-container {
            width: 100%;
        }

        .header,
        .footer {
            text-align: center;
            margin-bottom: 2mm;
        }

        .details table {
            width: 100%;
            border-collapse: collapse;
        }

        .details table th,
        .details table td {
            padding: 1mm 0;
            text-align: left;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 2mm 0;
        }

        .qr-code-container {
            text-align: center;
            /* Center the QR code */
            margin-left: 20%;
            height: 3.2cm;
        }
    </style>
</head>

<body>
    <div class="ticket-container">
        <div class="header">
            <h2>HIGHLINK ISGC</h2>
            <p>{{ $data->campany->name ?? 'N/A' }}</p>
            <p>P. O. Box {{ $data->campany->bus_owner_account->box ?? 'N/A' }}</p>
            <p>{{ $data->campany->bus_owner_account->region ?? 'N/A/' }},
                {{ $data->campany->bus_owner_account->country ?? 'N/A' }}</p>
            <p>Reg. No: {{ $data->campany->bus_owner_account->registration_number ?? 'N/A' }}</p>
            <p>TIN: {{ $data->campany->bus_owner_account->tin ?? 'N/A' }}</p>
            <p>VRN: {{ $data->campany->bus_owner_account->vrn ?? 'N/A' }}</p>
        </div>

        <div class="divider"></div>

        <div class="details">
            <table>
                <tr>
                    <td>Traveller Name:</td>
                    <td>{{ $data->customer_name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Traveller Contact:</td>
                    <td>{{ $data->customer_phone ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Booking number:</td>
                    <td>{{ $data->booking_code ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Bus number:</td>
                    <td>{{ $data->bus->bus_number ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Bus route:</td>
                    <td>
                        {{ $data->bus && $data->bus->route ? ($data->bus->route->from ?? 'N/A') . ' - ' . ($data->bus->route->to ?? 'N/A') : 'N/A' }}
                    </td>
                </tr>
                <tr>
                    <td>Traveller route:</td>
                    <td>{{ $data->pickup_point ?? 'N/A' }} - {{ $data->dropping_point ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Travel date:</td>
                    <td>{{ $data->travel_date ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Reporting time:</td>
                    <td>
                        {{ $data->travel_date ?? 'N/A' }} {{ $data->bus && $data->bus->route && $data->bus->route->route_start ? \Carbon\Carbon::parse($data->bus->route->route_start)->subMinutes(30)->format('h:i A') : 'N/A' }}
                    </td>
                </tr>
                <tr>
                    <td>Departure time:</td>
                    <td>
                        {{ $data->travel_date ?? 'N/A' }} {{ $data->bus && $data->bus->route && $data->bus->route->route_start ? \Carbon\Carbon::parse($data->bus->route->route_start)->format('h:i A') : 'N/A' }}
                    </td>
                </tr>
                <tr>
                    <td>Arrival date and time:</td>
                    <td>
                        {{ $data->travel_date ?? 'N/A' }} {{ $data->bus && $data->bus->route && $data->bus->route->route_end ? \Carbon\Carbon::parse($data->bus->route->route_end)->format('h:i A') : 'N/A' }}
                    </td>
                </tr>
                <tr>
                    <td>Seat number:</td>
                <td>{{ $data->seat ?? 'N/A' }}</td>
                </tr> 
                <tr>
                    <td>Service Amount:</td>
                    <td>{{ ceil($data->service + $data->vender_service ) ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>

        <div class="divider"></div>

        <div class="details">
            <h3>Insurance Details</h3>
            <table> 
                <tr>
                    <td>Policy:</td>
                    <td>Safiri salama - Domestic</td>
                </tr>
                <tr>
                    <td>Date and time of issue:</td>
                    <td>{{ $data->travel_date }}</td>
                </tr>
                <tr>
                    <td>Expire date and time:</td>
                    <td>{{ $data->insuranceDate }}</td>
                </tr> 
            </table>
        </div>

        <div class="divider"></div>

        <div class="details">
            <table>
                <tr>
                    <td>Conductor number:</td>
                    <td>{{ $data->bus->conductor ?? 'N/A' }}</td>
                </tr>
                @if ($data->vender_id)
                    <tr>
                        <td>Vendor Name:</td>
                        <td>{{ $data->vender->name }}</td>
                    </tr>
                    <tr>
                        <td>Vendor Number:</td>
                        <td>{{ $data->vender->contact }}</td>
                    </tr>
                @endif
            </table>
        </div>

        <div class="divider"></div>

        <div class="qr-code-container">
            {!! $data->qrcode !!}
        </div>

        <div class="divider"></div>

        <div class="footer">
            <div class="container">
                                <h6 class="text-muted">
                                    Nunua ticket mtandaoni kwa usalama wa hali ya juu wakati wowote na bila usumbufu kwa
                                    kutembelea www.hisgc.co.tz au piga <a href="tel:*149*46*36#">*149*46*36#</a> halafu
                                    fuata maelekezo ya kununua ticket au piga <a href="tel:+255755879793">+255 755 879
                                        793</a> kwa msaada zaidi. Highlink ISGC</h6>
                            </div>
        </div>
    </div>
</body>

</html>
