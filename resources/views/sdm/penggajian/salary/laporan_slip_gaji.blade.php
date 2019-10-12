<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

@php

function convertToRupiah($angka){
    $hasil =  number_format($angka,0, ',' , '.');
    return $hasil;
}

@endphp
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>.</title>

    <style>
        * {
            box-sizing: border-box;
            font-family: "Courier New", Times, serif;
        }
        /* Create two equal columns that floats next to each other */
        .column {
            float: left;
            width: 50%;
            padding: 10px;
            /* Should be removed. Only for demonstration */
        }

        /* Clear floats after the columns */
        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        /* .col1 {
            padding-left: 5px
        }

        .col4 {
            padding-left: 70px;
            float: right;
            text-align: right;

        }

        .col3 {
            padding-left: 10px
        } */
    </style>
</head>

<body onload="window.print()">
    <div>
        <hr size="2" color="#000000" />
        <center>
            <h2>LAPORAN SLIP GAJI</h2>
            <center>
    </div>
    <hr size="2" color="#000000" />

    <div class="row">
        <div class="column">
            <table>
                <tbody>
                    <tr>
                        <td>Nama Perusahaan</td>
                        <td>:</td>
                        <td>PT.Cipta Karya Mandiri</td>
                    </tr>
                    <tr>
                        <td>Periode</td>
                        <td>:</td>
                        <td>01/04/2015</td>
                    </tr>
                    <tr>
                        <td>Departement</td>
                        <td>:</td>
                        <td>HRD/ADMIN</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="column">
            <table>
                <tbody>
                    <tr>
                        <td>NIK</td>
                        <td>:</td>
                        <td>{{$salary->e_nik}}</td>
                    </tr>
                    <tr>
                        <td>Nama karyawan</td>
                        <td>:</td>
                        <td>{{$salary->e_name}}</td>
                    </tr>
                    <tr>
                        <td>Jabatan</td>
                        <td>:</td>
                        <td>HRD/ADMIN</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <hr size="2" color="#000000" />
    <div class="row">
        <div class="column">
            <h3 style="margin-top:0px">Penerimaan (+)</h3>
            <hr size="1" color="#000000" />
            <table>
                <tbody>
                  <tr>
                      <td class="col1">Gaji Pokok</td>
                      <td class="col3">: {{convertToRupiah($salary->e_salary)}}</td>
                  </tr>
                  <tr>
                      <td class="col1">Uang Makan</td>
                      <td class="col3">: {{convertToRupiah($salary->e_meal)}}</td>
                  </tr>
                  @foreach($reward as $data)
                    <tr>
                        <td class="col1">{{$data->b_name}}</td>
                        <td class="col3">: {{convertToRupiah($data->ebd_value)}}</td>
                    </tr>
                    @endforeach

                    @foreach($tunjangan as $data)
                      <tr>
                          <td class="col1">{{$data->b_name}}</td>
                          <td class="col3">: {{convertToRupiah($data->ebd_value)}}</td>
                      </tr>
                      @endforeach

                </tbody>
            </table>
        </div>

        <div class="column">
            <h3 style="margin-top:0px">Potongan (-)</h3>
            <hr size="1" color="#000000" />
            <table>
                <tbody>
                  @foreach($punishment as $data)
                    <tr>
                        <td class="col1">{{$data->b_name}}</td>
                        <td class="col3">: {{convertToRupiah($data->ebd_value)}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
    <hr size="1" color="#000000" />

    <div class="row">
        <div class="column">
            <table>
                <tbody>
                    <tr>
                        <th class="col1">Total Penerimaan</th>
                        <th class="col4">: {{'Rp.'.convertToRupiah($totalReceipts)}}</th>
                    </tr>
                    <tr>
                        <th class="col1">Gaji yang Diterima</th>
                        <th class="col4">: {{'Rp.'.convertToRupiah($totalSalary)}}</th>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <hr size="1" style="border-top: 1px dashed black;" />

    <div class="row">
        <div class="column">
            <table>
                <tbody>
                    <tr>
                        <td>Nama Perusahaan</td>
                        <td>:</td>
                        <td>PT.Cipta Karya Mandiri</td>
                    </tr>
                    <tr>
                        <td>Periode</td>
                        <td>:</td>
                        <td>01/04/2015</td>
                    </tr>
                    <tr>
                        <td>Departement</td>
                        <td>:</td>
                        <td>HRD/ADMIN</td>
                    </tr>
                    <tr>
                        <td>NIK</td>
                        <td>:</td>
                        <td>T11082</td>
                    </tr>
                    <tr>
                        <td>Nama karyawan</td>
                        <td>:</td>
                        <td>Nuris Akbar</td>
                    </tr>
                    <tr>
                        <td>Jabatan</td>
                        <td>:</td>
                        <td>HRD/ADMIN</td>
                    </tr>
                </tbody>
            </table>
        </div>


    </div>
</body>

</html>
