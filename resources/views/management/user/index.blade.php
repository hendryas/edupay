{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="page-heading">
        <h3>Management User</h3>
    </div>

    {{-- Tambahkan komponen dashboard kamu di sini --}}
    <div class="page-heading">
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        Data Management User
                    </h5>
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahUser">Tambah
                        +</a>
                </div>
                <div class="card-body">
                    <table class="table table-striped" id="table1">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Peran</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->role }}</td>
                                    <td>
                                        <a href="javascript:void(0)" class="btn btn-sm btn-warning btn-edit-user"
                                            data-id="{{ $user->id }}" data-name="{{ $user->name }}"
                                            data-email="{{ $user->email }}" data-role="{{ $user->role }}">Edit</a>
                                        <button type="button" class="btn btn-sm btn-danger btn-delete-user"
                                            data-id="{{ $user->id }}">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- ✅ Tampilkan link pagination --}}
                    {{ $users->links() }}
                </div>
            </div>

        </section>
    </div>

    {{-- Add Modal --}}
    <div class="modal fade" id="modalTambahUser" tabindex="-1" aria-labelledby="modalTambahUserLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="formTambahUser">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahUserLabel">Tambah User Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Peran</label>
                            <select name="role" class="form-control" required>
                                <option value="">-- Pilih Role --</option>
                                <option value="admin">Admin</option>
                                <option value="bendahara">Bendahara</option>
                                <option value="kepala_sekolah">Kepala Sekolah</option>
                                <option value="ortu">Orang Tua</option>
                            </select>
                            <div class="invalid-feedback" id="error-role"></div>
                        </div>
                        <div class="mb-3">
                            <label>Nama</label>
                            <input type="text" name="name" class="form-control" required>
                            <div class="invalid-feedback" id="error-name"></div>
                        </div>
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                            <div class="invalid-feedback" id="error-email"></div>
                        </div>
                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                            <div class="invalid-feedback" id="error-password"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div class="modal fade" id="modalEditUser" tabindex="-1" aria-labelledby="modalEditUserLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="formEditUser">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit_id"> <!-- Hidden ID -->

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditUserLabel">Edit User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Peran</label>
                            <select name="role" id="edit_role" class="form-control" required>
                                <option value="">-- Pilih Role --</option>
                                <option value="admin">Admin</option>
                                <option value="bendahara">Bendahara</option>
                                <option value="kepala_sekolah">Kepala Sekolah</option>
                                <option value="ortu">Orang Tua</option>
                            </select>
                            <div class="invalid-feedback" id="error-role"></div>
                        </div>

                        <div class="mb-3">
                            <label>Nama</label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                            <div class="invalid-feedback" id="error-name"></div>
                        </div>

                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" id="edit_email" class="form-control" required>
                            <div class="invalid-feedback" id="error-email"></div>
                        </div>

                        <div class="mb-3">
                            <label>Password (Opsional, isi jika mau ganti)</label>
                            <input type="password" name="password" id="edit_password" class="form-control">
                            <div class="invalid-feedback" id="error-password"></div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    {{-- Jquery --}}
    <script>
        $(document).ready(function() {
            // Tambah Data
            $('#formTambahUser').on('submit', function(e) {
                e.preventDefault(); // prevent reload

                // Bersihkan error sebelumnya
                $('.invalid-feedback').text('');
                $('input, select').removeClass('is-invalid');

                let formData = $(this).serialize(); // ambil semua data

                Swal.fire({
                    title: 'Memproses...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: "{{ route('admin.users.store') }}", // sesuaikan route kamu
                    type: "POST",
                    data: formData,
                    success: function(res) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'User berhasil ditambahkan.',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            $('#modalTambahUser').modal('hide');
                            $('#formTambahUser')[0].reset();
                            window.location.href = res.redirect;
                        });
                    },
                    error: function(xhr) {
                        Swal.close(); // tutup loading

                        if (xhr.status === 422) {
                            // Jika ada validasi error dari server
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                let input = $('[name="' + key + '"]');
                                input.addClass('is-invalid');
                                $('#error-' + key).text(value[0]);
                            });

                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Harap periksa kembali inputan Anda.'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Terjadi kesalahan. Silakan coba lagi.'
                            });
                        }
                    }
                });
            });

            // Lempat Data ke Form Edit
            $(document).on('click', '.btn-edit-user', function() {
                // Ambil data dari atribut data-*
                let id = $(this).data('id');
                let name = $(this).data('name');
                let email = $(this).data('email');
                let role = $(this).data('role');

                // Isi ke modal
                $('#edit_id').val(id);
                $('#edit_name').val(name);
                $('#edit_email').val(email);
                $('#edit_role').val(role);
                $('#edit_password').val(''); // kosongkan field password

                // Tampilkan modal
                $('#modalEditUser').modal('show');
            });

            // Proses submit form edit
            $('#formEditUser').on('submit', function(e) {
                e.preventDefault();

                // Bersihkan error dulu
                $('.invalid-feedback').text('');
                $('input, select').removeClass('is-invalid');

                let id = $('#edit_id').val();
                let formData = $(this).serialize();

                Swal.fire({
                    title: 'Memproses...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: "/admin/users/" + id, // Sesuaikan route update
                    type: "POST",
                    data: formData,
                    success: function(res) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'User berhasil diupdate.',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            $('#modalEditUser').modal('hide');
                            location.reload(); // reload tabel atau halaman
                        });
                    },
                    error: function(xhr) {
                        Swal.close();

                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                let input = $('[name="' + key + '"]');
                                input.addClass('is-invalid');
                                $('#error-' + key).text(value[0]);
                            });

                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Harap periksa kembali inputan Anda.'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Terjadi kesalahan. Silakan coba lagi.'
                            });
                        }
                    }
                });
            });

            // Delete Data
            $('.btn-delete-user').on('click', function() {
                const userId = $(this).data('id');

                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: 'Data akan dihapus.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/admin/users/' + userId,
                            type: 'POST',
                            data: {
                                _method: 'DELETE',
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(res) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Terhapus!',
                                    text: 'User berhasil dihapus.',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload(); // atau update tabel saja
                                });
                            },
                            error: function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: 'Terjadi kesalahan saat menghapus data.'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
    </script>
@endsection
