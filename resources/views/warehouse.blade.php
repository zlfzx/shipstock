@extends('layouts.global')
@section('title', 'Warehouse')

@section('content')
    <div class="row">
        <div class="col-md-10 col-sm-12">
            <div class="card">
                <div class="card-header border-0">
                  <h3 class="card-title">Daftar Warehouse</h3>
                  <div class="card-tools">
                    @if(auth()->user()->status == 'admin')
                    <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#modal-tambah">
                      <i class="fas fa-plus"></i>
                      Tambah
                    </button>
                    @endif
                  </div>
                </div>
                <div class="card-body">
                  <table class="table table-striped table-valign-middle text-center w-100 display nowrap" id="table">
                    <thead>
                    <tr>
                      <th>No</th>
                      <th>Nama</th>
                      <th>Alamat</th>
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
            <h5 class="modal-title">Tambah Warehouse</h5>
              <button class="close">&times;</button>
          </div>
          <form id="formTambah">
          <div class="modal-body">
            <div class="form-group">
              <label for="formNama">Nama</label>
              <input type="text" name="nama" class="form-control" id="formNama" placeholder="Masukkan nama Warehouse">
            </div>
            <div class="form-group">
              <label for="formAlamat">Alamat</label>
              <textarea name="alamat" class="form-control" id="formAlamat" cols="30" rows="10" placeholder="Tuliskan alamat warehouse"></textarea>
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
            <h5 class="modal-title">Edit Warehouse</h5>
              <button class="close">&times;</button>
          </div>
          <form id="formEdit">
          @method('PUT')
          <input type="hidden" name="id" id="editId">
          <div class="modal-body">
            <div class="form-group">
              <label for="editNama">Nama</label>
              <input type="text" name="nama" class="form-control" id="editNama" placeholder="Masukkan nama Warehouse">
            </div>
            <div class="form-group">
              <label for="editAlamat">Alamat</label>
              <textarea name="alamat" class="form-control" id="editAlamat" cols="30" rows="10" placeholder="Tuliskan alamat warehouse"></textarea>
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
    const status = "{{ auth()->user()->status }}"
    const table = $('#table').DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: "{{ route('warehouse.data') }}"
      },
      columns: [
        {data: 'index'},
        {data: 'nama'},
        {data: 'alamat'},
        {data: 'id', render: function (data, type, row) {
          if (status === 'admin') {
              return `
                <button class="btn btn-xs btn-warning btn-edit" data-id="${data}" data-nama="${row.nama}" data-alamat="${row.alamat}">Edit</button>
                <button class="btn btn-xs btn-danger btn-hapus" data-id="${data}" data-nama="${row.nama}">Hapus</button>`
          } else {
              return '-'
          }
        }}
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
        url: "{{ route('warehouse.store') }}",
        success: function (res) {
          table.draw()
          swal.fire('Berhasil', 'Warehouse berhasil ditambahkan', 'success')
          formTambah.trigger('reset')
          $('#modal-tambah').modal('hide')
        }
      })
    })

        // btn edit
    table.on('click', '.btn-edit', function() {
        const id = $(this).data('id')
        const nama = $(this).data('nama')
        const alamat = $(this).data('alamat')

        $('#editId').val(id)
        $('#editNama').val(nama)
        $('#editAlamat').val(alamat)
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
            url: "{{ route('warehouse.index') }}/" + data.get('id'),
            success: function (res) {
                table.draw();
                swal.fire('Berhasil', 'Warehouse berhasil diperbarui', 'success')
                $('#modal-edit').modal('hide')
            }
        })
    })

    // Hapus
    table.on('click', '.btn-hapus', function() {
        const id = $(this).data('id')
        const nama = $(this).data('nama')
        if (confirm('Hapus data Warehouse?')) {
            $.ajax({
                type: 'DELETE',
                url: "{{ route('warehouse.index') }}/" + id,
                success: function (res) {
                    swal.fire('Berhasil', 'Warehouse berhasil dihapus', 'success')
                    table.draw()
                }
            })
        }
    })
  </script>
@endsection
