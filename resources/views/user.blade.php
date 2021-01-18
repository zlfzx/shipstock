@extends('layouts.global')
@section('title', 'User')

@section('content')
    <div class="row">
        <div class="col-md-10 col-sm-12">
            <div class="card">
                <div class="card-header border-0">
                  <h3 class="card-title">Daftar User</h3>
                  <div class="card-tools">
                    <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#modal-tambah">
                      <i class="fas fa-plus"></i>
                      Tambah
                    </button>
                  </div>
                </div>
                <div class="card-body">
                  <table class="table table-striped table-valign-middle text-center w-100 display nowrap" id="table">
                    <thead>
                    <tr>
                      <th>No</th>
                      <th>Nama</th>
                      <th>Email</th>
                      <th>#</th>
                    </tr>
                    </thead>
                  </table>
                </div>
              </div>
        </div>
    </div>

    {{-- Modal Tambah --}}
    <div class="modal fade" id="modal-tambah">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Tambah User</h5>
              <button class="close">&times;</button>
          </div>
          <form id="formTambah">
          <div class="modal-body">
            <div class="form-group">
              <label for="formNama">Nama</label>
              <input type="text" name="nama" class="form-control" id="formNama" placeholder="Masukkan nama user">
            </div>
            <div class="form-group">
              <label for="formEmail">Email</label>
              <input type="text" name="email" class="form-control" id="formEmail" placeholder="Masukkan email user">
            </div>
            <div class="form-group">
                <label for="formPassword">Password</label>
                <input type="password" minlength="8" name="password" id="formPassword" class="form-control">
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success">Simpan</button>
          </div>
          </form>
        </div>
      </div>
    </div>

    {{-- Modal Edit --}}
    <div class="modal fade" id="modal-edit">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit User</h5>
              <button class="close">&times;</button>
          </div>
          <form id="formEdit">
          @method('PUT')
          <input type="hidden" name="id" id="editId">
          <div class="modal-body">
            <div class="form-group">
              <label for="editNama">Nama</label>
              <input type="text" name="nama" class="form-control" id="editNama" placeholder="Masukkan nama user">
            </div>
            <div class="form-group">
              <label for="editEmail">Email</label>
              <input type="text" name="email" class="form-control" id="editEmail" placeholder="Masukkan email user">
            </div>
            <div class="form-group">
                <label for="editPassword">Password</label>
                <input type="password" name="password" id="editPassword" class="form-control">
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success">Simpan</button>
          </div>
          </form>
        </div>
      </div>
    </div>
@endsection

@section('script')
  <script>
    const table = $('#table').DataTable({
        procssing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('user.data') }}"
        },
        columns: [
            {data: 'index'},
            {data: 'name'},
            {data: 'email'},
            {
                data: 'id', render: function (data, type, row) {
                    return `
                        <button class="btn btn-xs btn-warning btn-edit" data-id="${data}" data-nama="${row.name}" data-email="${row.email}">Edit</button>
                        <button class="btn btn-xs btn-danger btn-hapus" data-id="${data}" data-nama="${row.name}">Hapus</button>`
                }
            }
        ]
    })

    // Tambah data
    const formTambah = $('#formTambah').on('submit', function(e) {
      e.preventDefault();

      let data = new FormData(this);
      $.ajax({
        type: 'POST',
        data: data,
        processData: false,
        contentType: false,
        url: "{{ route('user.store') }}",
        success: function (res) {
            table.draw()
            swal.fire('Berhasil', 'User berhasil ditambahkan', 'success')
            formTambah.trigger('reset')
            $('#modal-tambah').modal('hide')
        }
      })
    })

        // btn edit
    table.on('click', '.btn-edit', function() {
        const id = $(this).data('id')
        const nama = $(this).data('nama')
        const email = $(this).data('email')

        $('#editId').val(id)
        $('#editNama').val(nama)
        $('#editEmail').val(email)
        $('#modal-edit').modal('show')
    })
    $('#formEdit').on('submit', function(e) {
        e.preventDefault()
        let data = new FormData(this);
        $.ajax({
            type: 'POST',
            data: data,
            processData: false,
            contentType:false,
            url: "{{ route('user.index') }}/" + data.get('id'),
            success: function (res) {
                table.draw();
                swal.fire('Berhasil', 'User berhasil diperbarui', 'success')
                $('#modal-edit').modal('hide')
            }
        })
    })

    // Hapus
    table.on('click', '.btn-hapus', function() {
        const id = $(this).data('id')
        const nama = $(this).data('nama')
        if (confirm('Hapus data User?')) {
            $.ajax({
                type: 'DELETE',
                url: "{{ route('user.index') }}/" + id,
                success: function (res) {
                    swal.fire('Berhasil', 'User berhasil dihapus', 'success')
                    table.draw()
                }
            })
        }
    })
  </script>
@endsection
