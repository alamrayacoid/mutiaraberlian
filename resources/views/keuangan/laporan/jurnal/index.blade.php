<!DOCTYPE html>

<html>
	<head>
		<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Laporan Jurnal</title>
        
        <link rel="stylesheet" type="text/css" href="{{asset('assets/css/app.css')}}">
		<link rel="stylesheet" type="text/css" href="{{ asset('modul_keuangan/bootstrap_4_1_3/css/bootstrap.min.css') }}">
		<link rel="stylesheet" type="text/css" href="{{ asset('modul_keuangan/font-awesome_4_7_0/css/font-awesome.min.css') }}">
		<link rel="stylesheet" type="text/css" href="{{ asset('modul_keuangan/css/style.css') }}">
    	<link rel="stylesheet" type="text/css" href="{{ asset('modul_keuangan/js/vendors/select2/dist/css/select2.min.css') }}">
    	<link rel="stylesheet" type="text/css" href="{{ asset('modul_keuangan/js/vendors/datepicker/dist/datepicker.min.css') }}">
    	<link rel="stylesheet" type="text/css" href="{{ asset('modul_keuangan/js/vendors/toast/dist/jquery.toast.min.css') }}">
        <link rel="stylesheet" href="{{asset('assets/select2/select2.css')}}">
        <link rel="stylesheet" href="{{asset('assets/select2/select2-bootstrap.css')}}">

		<style>

			body{
				background: rgba(0,0,0, 0.5);
			}

			/* select 2 custom element */
	            .select2-container .select2-selection--single{
	                border-radius: 0px;
	                border: 1px solid #ddd;
	                height: 30px;
	                font-weight: normal;
	                color: #666;
	                outline: none;
	                font-size: 9pt;
	            }

	            .select2-dropdown{
	                border: 1px solid #ddd;
	                font-size: 9pt;
	            }

		    .navbar-brand {
		    	padding-left: 30px;
		    }

		    .navbar-nav {
		      flex-direction: row;
		      padding-right: 40px; 
		    }
		    
		    .nav-link {
		      padding-right: .5rem !important;
		      padding-left: .5rem !important;
		    }
		    
		    /* Fixes dropdown menus placed on the right side */
		    .ml-auto .dropdown-menu {
		      left: auto !important;
		      right: 0px;
		    }

		    .nav-item{
		    	color: white;
		    }

		    .navbar-nav li{
		        border-left: 1px solid rgba(255, 255, 255, 0.1);
		        padding: 0px 25px;
		        cursor: pointer;
		    }

		    .navbar-nav li:last-child{
		    	border-right: 1px solid rgba(255, 255, 255, 0.1);
		    }

		    .ctn-nav {
		    	background: rgba(0,0,0, 0.7);
		    	position: fixed;
		    	bottom: 1.5em;
		    	z-index: 1000;
		    	font-size: 10pt;
		    	box-shadow: 0px 0px 10px #aaa;
		    	border-radius: 10px
		    }

		    #title-table{
		    	padding: 0px;
		    }

		    #table-data{
		    	font-size: 9pt;
		    }

		    #table-data td, #table-data th {
		    	padding: 5px 10px;
		    	border: 0px solid #eee;
		    }

		    #table-data td.head{
		    	border: 1px solid white;
		    	background: #0099CC;
		    	color: white;
		    	font-weight: bold;
		    	text-align: center;
		    }

		    #table-data td.sub-head{
		    	border: 1px solid #0099CC;
		    	color: #333;
		    	font-weight: bold;
		    	text-align: center;
		    }

		    #contentnya{
	          width: 80%;
	          padding: 0px 20px;
	          background: white;
	          min-height: 700px;
	          border-radius: 2px;
	          margin: 0 auto;
	        }

		</style>

		<style type="text/css" media="print">
          @page { size: portrait; }
          nav{
            display: none;
          }

          .ctn-nav{
            display: none;
          }

          #contentnya{
          	width: 100%;
          	padding: 0px;
          	margin-top: -80px;
          }

          #table-data th, #table-data td{
             /*background-color: #0099CC !important;*/
             /*color: white;*/
             -webkit-print-color-adjust: exact;
          }

          #table-data td.not-same{
             color: red !important;
             -webkit-print-color-adjust: exact;
          }

          .page-break { display: block; page-break-before: always; }
      	</style>
	</head>

	<body>
		<div id="vue-element">
			<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark" style="box-shadow: 0px 5px 10px #555;">
			    <a class="navbar-brand" href="{{ url('/') }}">{{-- {{ jurnal()->companyName }} --}} CV. Mutiara Berlian</a>

			    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
			      <span class="navbar-toggler-icon"></span>
			    </button>

			    <div class="collapse navbar-collapse" id="navbarCollapse">
			      <ul class="navbar-nav ml-auto">

			      	<li class="nav-item">
			      	  <a href="{{ route('laporankeuangan.index') }}" style="color: #ffbb33;">
			          	<i class="fa fa-backward" title="Kembali Ke Menu Laporan"></i>
			          </a>
			        </li>

			        <li class="nav-item">
			          	<i class="fa fa-print" title="Print Laporan" @click="print"></i>
			        </li>

			        <!-- <li class="nav-item dropdown" title="Download Laporan">
			          	<i class="fa fa-download" id="dropdownMenuButton" data-toggle="dropdown"></i>

			            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						    <a class="dropdown-item" href="#" style="font-size: 10pt;" @click='downloadPdf'>
						    	<i class="fa fa-file-pdf-o" style="font-weight: bold;"></i> &nbsp; Download PDF
						    </a>

						    <div class="dropdown-divider"></div>

						    <a class="dropdown-item" href="#" style="font-size: 10pt;" @click='downloadExcel'>
						    	<i class="fa fa-file-excel-o" style="font-weight: bold;"></i> &nbsp; Download Excel
						    </a>
					    </div>
			        </li> -->

			        <li class="nav-item">
			          <i class="fa fa-sliders" title="Pengaturan Laporan" @click="showSetting"></i>
			        </li>

			      </ul>
			    </div>
			</nav>

			<template v-if="laporanReady == 'true' && downloadingResource">
				aaa
			</template>

			<template v-if="laporanReady == 'true' && !downloadingResource">
				<div class="container-fluid" style="background: none; margin-top: 70px; padding: 10px 30px;">
					<div id="contentnya">
						<table width="100%" border="0" style="border-bottom: 1px solid #333;" {{-- v-if="pageNow == 1" v-cloak --}}>
				          <thead>
				            <tr>
				              <th style="text-align: center; font-size: 12pt; font-weight: 600; padding-top: 10px; color: #666;" colspan="2">
				              	CV. Mutiara Berlian
				              </th>
				            </tr>

				            <tr>
				              <th style="text-align: center; font-size: 14pt; color: #0099CC; font-weight: bold;" colspan="2">
				              	Laporan Jurnal Umum
				              </th>
				            </tr>

				            <tr>
				              <th style="text-align: center; font-size: 8pt; font-weight: 500; padding-bottom: 10px; color: #666; font-style: italic;">
				              	
				              	@if(isset($_GET['lap_tanggal_awal']) && isset($_GET['lap_tanggal_akhir']))
				              		tanggal : {{ $_GET['lap_tanggal_awal'] }} - {{ $_GET['lap_tanggal_akhir'] }}
				              	@endif
				              </th>
				            </tr>
				          </thead>
				        </table>

				        <table width="100%">
				        	<thead>
				        		<tr>
				        			<th style="font-size: 7pt; text-align: left; color: #888; font-style: italic; padding-top: 5px;">Menampilkan Laporan Milik : &nbsp; @{{ single.cabang }}</th>
				        			<th style="font-size: 7pt; text-align: right; color: #888; font-style: italic; padding-top: 5px;">Angka Dalam Satuan Rupiah (Rp)</th>
				        		</tr>
				        	</thead>
				        </table>

				        <table id="table-data" width="100%" style="font-size: 8pt; margin-top: 15px;" border="0">
				        	<thead>
				        		<tr>
				        			<th width="8%" style="background: #e5e5e5; color: #333; text-align: center; padding: 5px;">
				        				Tanggal
				        			</th>
				        			<th width="10%" style="background: #e5e5e5; color: #333; text-align: center; padding: 5px;">
				        				No. Transaksi
				        			</th>
				        			<th width="15%%" style="background: #e5e5e5; color: #333; text-align: center; padding: 5px;">
				        				Pemilik Jurnal
				        			</th>
				        			<th width="25%" style="background: #e5e5e5; color: #333; text-align: center; padding: 5px;">
				        				Keterangan
				        			</th>
				        			<th width="9%" style="background: #e5e5e5; color: #333; text-align: center; padding: 5px;">
				        				kode COA
				        			</th>
				        			<th style="background: #e5e5e5; color: #333; text-align: center; padding: 5px;">
				        				Nama COA
				        			</th>
				        			<th style="background: #e5e5e5; color: #333; text-align: center; padding: 5px;">
				        				Debet
				        			</th>
				        			<th style="background: #e5e5e5; color: #333; text-align: center; padding: 5px;">
				        				Kredit
				        			</th>
				        		</tr>
				        	</thead>

				        	<tbody>
				        		<template v-for="(jurnal, idx) in data">
				        			<tr v-for="(detail, idx) in jurnal.detail">
					        			<td style="text-align: center; padding: 5px;">@{{ humanizeDate(jurnal.jr_tanggal_trans) }}</td>
					        			<td style="text-align: center; padding: 5px;">@{{ jurnal.jr_nota_ref }}</td>
					        			<td style="text-align: left; padding: 5px;">@{{ jurnal.c_name }}</td>
					        			<td style="text-align: left; padding: 5px;">@{{ jurnal.jr_keterangan }}</td>
					        			<td style="text-align: center; padding: 5px;">@{{ detail.ak_nomor }}</td>
					        			<td style="text-align: left; padding: 5px;">@{{ detail.ak_nama }}</td>
					        			<td style="text-align: right; padding: 5px;">
					        				@{{ (detail.jrdt_dk == 'D') ? humanizePrice(detail.jrdt_value) :'0.00' }}
					        			</td>
					        			<td style="text-align: right; padding: 5px;">
					        				@{{ (detail.jrdt_dk == 'K') ? humanizePrice(detail.jrdt_value) :'0.00' }}
					        			</td>
					        		</tr>
					        		<tr>
					        			<td colspan="6" style="background: #e5e5e5; padding: 5px;">&nbsp;</td>
					        			<td style="background: #e5e5e5; text-align: right; font-weight: bold; padding: 5px;">
					        				@{{ humanizePrice(jurnal.debet) }}
					        			</td>
					        			<td style="background: #e5e5e5; text-align: right; font-weight: bold; padding: 5px;">
					        				@{{ humanizePrice(jurnal.kredit) }}
					        			</td>
					        		</tr>
				        		</template>

				        		<tr v-if="!data.length">
				        			<td colspan="8" style="text-align: center; padding: 5px;">Tidak Ada Transaksi Pada Tanggal Ini.</td>
				        		</tr>
				        	</tbody>
				        </table>

				    </div>
				</div>
			</template>

			<template v-if="laporanReady == 'false'">
				<div class="col-md-12" style="font-size: 9pt;">
					<div class="row">
						<div class="col-md-4" style="margin: 10rem auto">
							<div class="card">
	                            <div class="card-header bordered p-2">
	                                <div class="header-block">
	                                    <h3 class="title"> Setting Laporan Jurnal Umum </h3>
	                                </div>
	                            </div>
	                            <div class="card-block">
	                                <form id="data-form" enctype="multipart/form-data" action="{{ Route('laporan.keuangan.jurnal_umum') }}">
	                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" readonly>
	                                    <section>
	                                        <div class="row keuangan-form" style="border-bottom: 0px solid #ddd; padding-bottom: 30px;">
	                                            <div class="col-md-12" style="border-right: 1px solid #ddd;">
	                                                <div class="col-md-12">
	                                                	<div class="row" style="margin-top: 0px;">
	                                                        <div class="col-md-5 label">Jurnal Milik</div>
	                                                        <div class="col-md-7">
	                                                            <vue-select :name="'lap_cabang'" :id="'lap_cabang'" :options="lap_cabang" :search="false" v-model="single.lap_cabang"></vue-select>
	                                                        </div>
	                                                    </div>

	                                                    <div class="row" style="margin-top: 15px;">
	                                                        <div class="col-md-5 label">Jenis Laporan</div>
	                                                        <div class="col-md-7">
	                                                            <vue-select :name="'lap_jenis'" :id="'lap_jenis'" :options="lap_jenis" :search="false" v-model="single.lap_jenis"></vue-select>
	                                                        </div>
	                                                    </div>

	                                                    <div class="row" style="margin-top: 15px;">
	                                                        <div class="col-md-5 label">Rentang Waktu</div>
	                                                        <div class="col-md-7">
	                                                        	<table width="100%">
	                                                        		<thead>
	                                                        			<tr>
	                                                        				<td>
	                                                        					<vue-datepicker :name="'lap_tanggal_awal'" :id="'lap_tanggal_awal'" :class="'form-control'" :placeholder="'Tgl Awal'" :title="'Tidak Boleh Kosong'" :readonly="true" v-model="single.lap_tanggal_awal" :style="'font-size: 8pt;'" @input="tanggalAwalChange"></vue-datepicker>
	                                                        				</td>

	                                                        				<td width="10%" style="padding: 0px 5px;">
	                                                        					s/d
	                                                        				</td>

	                                                        				<td>
	                                                        					<vue-datepicker :name="'lap_tanggal_akhir'" :id="'lap_tanggal_akhir'" :class="'form-control'" :placeholder="'Tgl Akhir'" :title="'Tidak Boleh Kosong'" :readonly="true" v-model="single.lap_tanggal_akhir" :style="'font-size: 8pt;'"></vue-datepicker>
	                                                        				</td>
	                                                        			</tr>
	                                                        		</thead>
	                                                        	</table>
	                                                        </div>
	                                                    </div>

	                                                    <!-- <div class="row" style="margin-top: 15px;">
	                                                        <div class="col-md-5 label">Tampilkan Nama COA</div>
	                                                        <div class="col-md-3">
	                                                            <vue-select :name="'lap_nama'" :id="'lap_nama'" :options="lap_nama" :search="false" v-model="single.lap_nama"></vue-select>
	                                                        </div>
	                                                    </div> -->

	                                                    <!-- <hr> -->

	                                                    <div class="row" style="margin-top: -10px;">
	                                                        <div class="col-md-12 label">

	                                                        </div>
	                                                    </div>
	                                                </div>
	                                            </div>
	                                        </div>
	                                    </section>
	                                </form>
	                            </div>

	                            <div class="card-footer text-right">
		                            <button type="button" class="btn btn-primary btn-sm" @click="terapkan" type="button">Terapkan</button>
		                        </div>
	                        </div>
						</div>
					</div>
				</div>
			</template>

			<div class="modal fade" id="modal-setting" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content" style="border-radius: 5px;">
						<div class="modal-header">
							<span class="modal-title keuangan" id="myModalLabel">Pilih mutasi yang akan diperbarui</span>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					        	<span aria-hidden="true">&times;</span>
					        </button>
						</div>
						<div class="modal-body" style="font-size: 9.5pt;">
							<form id="data-form" enctype="multipart/form-data" action="{{ Route('laporan.keuangan.jurnal_umum') }}">
								<input type="hidden" name="_token" value="{{ csrf_token() }}" readonly>
								<div class="row keuangan-form" style="border-bottom: 0px solid #ddd; padding-bottom: 30px;">
	                                <div class="col-md-12" style="border-right: 1px solid #ddd;">
	                                    <div class="col-md-12">
	                                    	<div class="row" style="margin-top: 0px;">
	                                            <div class="col-md-5 label">Jurnal Milik</div>
	                                            <div class="col-md-7">
	                                                <vue-select :name="'lap_cabang'" :id="'lap_cabang'" :options="lap_cabang" :search="false" v-model="single.lap_cabang"></vue-select>
	                                            </div>
	                                        </div>

	                                        <div class="row" style="margin-top: 15px;">
	                                            <div class="col-md-5 label">Jenis Laporan</div>
	                                            <div class="col-md-7">
	                                                <vue-select :name="'lap_jenis'" :id="'lap_jenis'" :options="lap_jenis" :search="false" v-model="single.lap_jenis"></vue-select>
	                                            </div>
	                                        </div>

	                                        <div class="row" style="margin-top: 15px;">
	                                            <div class="col-md-5 label">Rentang Waktu</div>
	                                            <div class="col-md-7">
	                                            	<table width="100%">
	                                            		<thead>
	                                            			<tr>
	                                            				<td>
	                                            					<vue-datepicker :name="'lap_tanggal_awal'" :id="'lap_tanggal_awal'" :class="'form-control'" :placeholder="'Tgl Awal'" :title="'Tidak Boleh Kosong'" :readonly="true" v-model="single.lap_tanggal_awal" :style="'font-size: 8pt;'" @input="tanggalAwalChange"></vue-datepicker>
	                                            				</td>

	                                            				<td width="10%" style="padding: 0px 5px;">
	                                            					s/d
	                                            				</td>

	                                            				<td>
	                                            					<vue-datepicker :name="'lap_tanggal_akhir'" :id="'lap_tanggal_akhir'" :class="'form-control'" :placeholder="'Tgl Akhir'" :title="'Tidak Boleh Kosong'" :readonly="true" v-model="single.lap_tanggal_akhir" :style="'font-size: 8pt;'"></vue-datepicker>
	                                            				</td>
	                                            			</tr>
	                                            		</thead>
	                                            	</table>
	                                            </div>
	                                        </div>

	                                        <!-- <div class="row" style="margin-top: 15px;">
	                                            <div class="col-md-5 label">Tampilkan Nama COA</div>
	                                            <div class="col-md-3">
	                                                <vue-select :name="'lap_nama'" :id="'lap_nama'" :options="lap_nama" :search="false" v-model="single.lap_nama"></vue-select>
	                                            </div>
	                                        </div> -->

	                                        <!-- <hr> -->

	                                        <div class="row" style="margin-top: -10px;">
	                                            <div class="col-md-12 label">

	                                            </div>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
                        	</form>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-primary btn-sm" @click="terapkan" type="button">Terapkan</button>
						</div>
					</div>
				</div>
			</div>

	        <iframe style="display: none;" id='pdfIframe' src=''/></iframe>
		</div>

		<script src="{{ asset('modul_keuangan/js/jquery_3_3_1.min.js') }}"></script>
		<script src="{{ asset('modul_keuangan/bootstrap_4_1_3/js/bootstrap.min.js') }}"></script>
		<script src="{{asset('assets/select2/select2.js')}}"></script>
    	<script src="{{ asset('modul_keuangan/js/vendors/datepicker/dist/datepicker.min.js') }}"></script>
    	<script src="{{ asset('modul_keuangan/js/vendors/axios/axios.min.js') }}"></script>
    	<script src="{{ asset('modul_keuangan/js/vendors/toast/dist/jquery.toast.min.js') }}"></script>

    	<!-- vue -->
    	<script src="{{ asset('modul_keuangan/js/vendors/vue/vue.js') }}"></script>
    	<script src="{{asset('modul_keuangan/js/vendors/vue/components/select/select.component.js')}}"></script>
    	<script src="{{ asset('modul_keuangan/js/vendors/vue/components/datepicker/datepicker.component.js') }}"></script>

    	<script type="text/javascript">
			var app = new Vue({
    			el: '#vue-element',
    			data: {
    				downloadingResource: false,
    				resourceError: false,
    				url: new URL(window.location.href),
    				laporanReady : '{{ (isset($_GET["_token"])) ? "true" : "false" }}',

    				lap_jenis: [
    					{
    						id: 'MK',
    						text: 'Jurnal Pada Mutasi Antar Kas'
    					},

    					{
    						id: 'TK',
    						text: 'Jurnal Pada Transaksi Kas'
    					},

    					{
    						id: 'TM',
    						text: 'Jurnal Pada Transaksi Memorial'
    					}
    				],

    				lap_nama: [
    					{
    						id: 'Y',
    						text: 'Ya.'
    					},

    					{
    						id: 'T',
    						text: 'Tidak.'
    					},
    				],

    				lap_cabang: [],

    				data: [],

    				single: {
    					lap_jenis: 'MK',
    					lap_nama: 'Y',
    					lap_tanggal_awal: '',
    					lap_tanggal_akhir: '',
    					lap_cabang: '',
    					cabang: '',
    				}
    			},

	            mounted: function(){
	            	console.log('Vue Ready');
	            	this.lap_cabang = {!! $cabang !!};

	            	if(this.lap_cabang.length){
                    	this.single.lap_cabang = this.lap_cabang[0].id;
                    }

	            	if(this.laporanReady == 'true'){
	            		this.downloadingResource = true;

	            		axios.get("{{ Route('laporan.keuangan.jurnal_umum.resource') }}?"+this.url.searchParams)
	                        .then((response) => {
	                            this.downloadingResource = false;
	                            this.data = response.data.data;
	                            this.single.cabang = response.data.namaCabang;

	                        }).catch((e) => {
	                            this.downloadingResource = false;
	                            this.resourceError = true;
	                            console.log('System Bermasalah '+e);
	                        })
	            	}
	            },

	            computed: {
	            	
	            },

	            watch: {
	            	
	            },

	            methods: {

	            	terapkan: function(e){
	            		e.preventDefault();
	            		e.stopImmediatePropagation();

	            		$('#data-form').submit();
	            	},

	            	showSetting: function(evt){
	            		evt.preventDefault();
	                	evt.stopImmediatePropagation();

	                	$('#modal-setting').modal('show');
	            	},

	            	downloadPdf: function(evt){
	            		evt.preventDefault();
	                	evt.stopImmediatePropagation();

	                	$.toast({
						    text: "Sedang Mendownload Laporan PDF",
                            showHideTransition: 'slide',
                            position: 'bottom-right',
                            icon: 'info',
                            hideAfter: 10000,
                            showHideTransition: 'slide',
                            allowToastClose: false,
                            stack: false
						});

	                    // $('#pdfIframe').attr('src', '?'+that.url.searchParams)
	            	},

	            	downloadExcel: function(evt){
	            		evt.preventDefault();
	                	evt.stopImmediatePropagation();

	                	$.toast({
                            text: "Sedang Mendownload Laporan EXCEL",
                            showHideTransition: 'slide',
                            position: 'bottom-right',
                            icon: 'info',
                            hideAfter: 10000,
                            showHideTransition: 'slide',
                            allowToastClose: false,
                            stack: false
                        });

                        // $('#pdfIframe').attr('src', '?'+that.url.searchParams)
	            	},

	            	print: function(evt){
	            		evt.preventDefault();
	            		evt.stopImmediatePropagation();

	            		$.toast({
                            text: "Sedang Mencetak Laporan",
                            showHideTransition: 'slide',
                            position: 'bottom-right',
                            icon: 'info',
                            hideAfter: 8000,
                            showHideTransition: 'slide',
                            allowToastClose: false,
                            stack: false
                        });

	            		window.print();

	            		// $('#pdfIframe').attr('src', '?'+that.url.searchParams)
	            	},

	            	tanggalAwalChange: function(e){
	                	$('#lap_tanggal_akhir').val("");
	                	$('#lap_tanggal_akhir').datepicker("setStartDate", e);
	                },

	            	typeChange: function(e){
	                	
	                },

	                akunChange:function(e){
	                	
	                },

	            	humanizePrice: function(alpha){
		            	var kl = alpha.toString().replace('-', '');
		                bilangan = kl;
		                var commas = '00';
		                if(bilangan.split('.').length > 1){
		                  commas = bilangan.split('.')[1];
		                  bilangan = bilangan.split('.')[0];
		                }
		                var number_string = bilangan.toString(),
		                  sisa  = number_string.length % 3,
		                  rupiah  = number_string.substr(0, sisa),
		                  ribuan  = number_string.substr(sisa).match(/\d{3}/g);
		                if (ribuan) {
		                  separator = sisa ? ',' : '';
		                  rupiah += separator + ribuan.join(',');
		                }
		                // Cetak hasil
		                commas = (commas.length == 1) ? commas+"0" : commas;
		                return rupiah+'.'+commas; // Hasil: 23.456.789
	                },

	                humanizeDate(date){
	                	let d = date.split('-')[2]+'/'+date.split('-')[1]+'/'+date.split('-')[0];

	                	return d;
	                },

	                prosesLaporan: function(evt){
	                	evt.preventDefault();
	                	evt.stopImmediatePropagation();

	                },

	                validate: function(){

	                },
	            }
    		})
    	</script>
	</body>
</html>
