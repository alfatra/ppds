@extends('layouts.master')
@section('title')
	Data PPDS
@endsection
@push('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Responsive datatable examples -->
    <link href="{{ URL::asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endpush
@section('content')
	<x-breadcrumb pagetitle="PPDS" title="Data PPDS" />

	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<div>
                        {{-- Mengarahkan ke halaman form tambah data --}}
						<a href="{{ route('ppds.create') }}" class="btn btn-success mb-2"><i class="mdi mdi-plus me-2"></i> Tambah Data PPDS</a>
					</div>

					<div class="table-responsive mt-3">
						<table class="table table-centered datatable dt-responsive nowrap"
							style="border-collapse: collapse; border-spacing: 0; width: 100%;">
							<thead class="thead-light">
								<tr>
									<th style="width: 20px;">
										<div class="form-check">
											<input type="checkbox" class="form-check-input" id="select-all-check">
											<label class="form-check-label mb-0" for="select-all-check">&nbsp;</label>
										</div>
									</th>
									<th>Nama Lengkap</th>
									<th>Email</th>
									<th>Telepon</th>
									<th>Agama</th>
									<th>Tanggal Bergabung</th>
									<th style="width: 120px;">Aksi</th>
								</tr>
							</thead>
							<tbody>
                                @foreach ($ppds_list as $ppds)
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="check-{{ $ppds->id }}">
                                                <label class="form-check-label mb-0" for="check-{{ $ppds->id }}">&nbsp;</label>
                                            </div>
                                        </td>
                                        <td>{{ $ppds->nama_lengkap }}</td>
                                        <td>{{ $ppds->email }}</td>
                                        <td>{{ $ppds->telepon ?? '-' }}</td>
                                        <td>{{ $ppds->agama ?? '-' }}</td>
                                        <td>{{ $ppds->created_at->format('d M, Y') }}</td>
                                        <td id="tooltip-container-{{ $ppds->id }}">
                                            {{-- TODO: Arahkan ke halaman edit & proses hapus --}}
                                            <a href="{{ route('ppds.edit', $ppds->id) }}" class="me-3 text-primary"
                                                data-bs-container="#tooltip-container-{{ $ppds->id }}" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="Edit"><i
                                                    class="mdi mdi-pencil font-size-18"></i></a>
                                            <a href="#" class="text-danger"
                                                onclick="event.preventDefault(); showDeleteConfirmation({{ $ppds->id }});"
                                                data-bs-container="#tooltip-container-{{ $ppds->id }}" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="Hapus"><i
                                                    class="mdi mdi-trash-can font-size-18"></i></a>
                                            <form id="delete-form-{{ $ppds->id }}" action="{{ route('ppds.destroy', $ppds->id) }}" method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div> <!-- end col -->
	</div>
	<!-- end row -->
@endsection
@push('script')
	<!-- Required datatable js -->
    <script src="{{ URL::asset('build/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    {{-- Sweet Alert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ URL::asset('build/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>

    <!-- Responsive examples -->
    <script src="{{ URL::asset('build/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

	<script src="{{ URL::asset('build/js/app.js') }}"></script>

    {{-- Inisialisasi DataTables & Tooltip --}}
    <script>
        $(document).ready(function() {
            $('.datatable').DataTable({
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Cari data..."
                }
            });

            // Inisialisasi tooltip Bootstrap
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            @if (session('success'))
                Swal.fire({
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    icon: 'success',
                    confirmButtonColor: '#5664d2',
                    confirmButtonText: 'Oke'
                });
            @endif
        });

        // Fungsi untuk menampilkan konfirmasi hapus dengan SweetAlert2
        function showDeleteConfirmation(id) {
            Swal.fire({
                title: 'Yakin ingin menghapus data ini?',
                text: "Data yang sudah dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#5664d2', // Warna primer dari tema
                cancelButtonColor: '#ff3d60',  // Warna danger dari tema
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika dikonfirmasi, submit form hapus yang sesuai
                    document.getElementById('delete-form-' + id).submit();
                }
            })
        }
    </script>
@endpush
