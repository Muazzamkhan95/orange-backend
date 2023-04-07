<a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>

	<!-- Ansta Scripts -->
	<!-- Core -->
	<script src="{{ asset('assets/plugins/jquery/dist/jquery.min.js') }}"></script>
	{{-- <script src="{{ asset('assets/js/popp  er.js') }}"></script> --}}
	<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>

	<!-- Echarts JS -->
	{{-- <script src="{{ asset('assets/plugins/chart-echarts/echarts.js') }}"></script> --}}

	<!-- Fullside-menu Js-->
	<script src="{{ asset('assets/plugins/toggle-sidebar/js/sidemenu.js') }}"></script>

	<!-- Custom scroll bar Js-->
	<script src="{{ asset('assets/plugins/customscroll/jquery.mCustomScrollbar.concat.min.js') }}"></script>

	<!-- peitychart -->
	{{-- <script src="{{ asset('assets/plugins/peitychart/jquery.peity.min.js') }}"></script> --}}
	{{-- <script src="{{ asset('assets/plugins/peitychart/peitychart.init.js') }}"></script> --}}

	<!-- Vector Plugin -->
	{{-- <script src="{{ asset('assets/plugins/jvectormap/jquery-jvectormap-2.0.2.min.js') }}"></script> --}}
	{{-- <script src="{{ asset('assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script> --}}
	{{-- <script src="{{ asset('assets/plugins/jvectormap/gdp-data.js') }}"></script> --}}
	{{-- <script src="{{ asset('assets/plugins/jvectormap/jquery-jvectormap-us-aea-en.js') }}"></script> --}}
	{{-- <script src="{{ asset('assets/plugins/jvectormap/jquery-jvectormap-uk-mill-en.js') }}"></script>
	<script src="{{ asset('assets/plugins/jvectormap/jquery-jvectormap-au-mill.js') }}"></script>
	<script src="{{ asset('assets/plugins/jvectormap/jquery-jvectormap-ca-lcc.js') }}"></script> --}}
	{{-- <script src="{{ asset('assets/js/dashboard2map.js') }}"></script> --}}

	<!-- Ansta JS -->
	<script src="{{ asset('assets/js/custom.js') }}"></script>
	<script src="{{ asset('datatable/jquery.dataTables.min.js') }}"></script>
	{{-- <script src="{{ asset('assets/js/dashboard-sales.js') }}"></script> --}}
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script> --}}
    <script>
        $(document).ready( function () {
            $('#myTable').DataTable({
                scrollX: true,
            });
            $('#myTable1').DataTable({
            });
            $('#myTable2').DataTable({
                scrollX: true,
                "columnDefs": [
                    { "width": "150px", "targets": 1 }
                ]
            });
        } );
    </script>
