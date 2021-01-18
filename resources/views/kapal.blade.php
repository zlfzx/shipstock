@extends('layouts.global')
@section('title', 'Kapal')

@section('content')
    <div class="row">
        <div class="col-md-10 col-sm-12">
            <div class="card">
                <div class="card-header border-0">
                  <h3 class="card-title">Daftar Kapal</h3>
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
                      <th>Kapten</th>
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
            <h5 class="modal-title">Tambah Kapal</h5>
              <button class="close">&times;</button>
          </div>
          <form id="formTambah">
          <div class="modal-body">
            <div class="form-group">
              <label for="formNama">Nama Kapal</label>
              <input type="text" name="nama" class="form-control" id="formNama" placeholder="Masukkan nama Kapal">
            </div>
            <div class="form-group">
              <label for="formKapten">Nama Kapten</label>
              <input type="text" name="kapten" class="form-control" id="formKapten" placeholder="Masukkan nama Kapten">
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
            <h5 class="modal-title">Ubah Kapal</h5>
              <button class="close">&times;</button>
          </div>
          <form id="formEdit">
          @method('PUT')
          <input type="hidden" name="id" id="editId">
          <div class="modal-body">
            <div class="form-group">
              <label for="editNama">Nama Kapal</label>
              <input type="text" name="nama" class="form-control" id="editNama" placeholder="Masukkan nama Kapal">
            </div>
            <div class="form-group">
              <label for="editKapten">Nama Kapten</label>
              <input type="text" name="kapten" class="form-control" id="editKapten" placeholder="Masukkan nama Kapten">
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
        url: "{{ route('kapal.data') }}"
      },
      columns: [
        {data: 'index'},
        {data: 'nama'},
        {data: 'kapten'},
        {data: 'id', render: function (data, type, row) {
          if (status === 'admin') {
              return `
                <button class="btn btn-xs btn-warning btn-edit" data-id="${data}" data-nama="${row.nama}" data-kapten="${row.kapten}">Edit</button>
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
        url: "{{ route('kapal.store') }}",
        success: function (res) {
          table.draw()
          swal.fire('Berhasil', 'Kapal berhasil ditambahkan', 'success')
          formTambah.trigger('reset')
          $('#modal-tambah').modal('hide')
        }
      })
    })

    // btn edit
    table.on('click', '.btn-edit', function() {
        const id = $(this).data('id')
        const nama = $(this).data('nama')
        const kapten = $(this).data('kapten')

        $('#editId').val(id)
        $('#editNama').val(nama)
        $('#editKapten').val(kapten)
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
            url: "{{ route('kapal.index') }}/" + data.get('id'),
            success: function (res) {
                table.draw();
                swal.fire('Berhasil', 'Kapal berhasil diperbarui', 'success')
                $('#modal-edit').modal('hide')
            }
        })
    })

    // Hapus
    table.on('click', '.btn-hapus', function() {
        const id = $(this).data('id')
        const nama = $(this).data('nama')
        if (confirm('Hapus data Kapal?')) {
            $.ajax({
                type: 'DELETE',
                url: "{{ route('kapal.index') }}/" + id,
                success: function (res) {
                    swal.fire('Berhasil', 'Kapal berhasil dihapus', 'success')
                    table.draw()
                }
            })
        }
    })
  </script>
@endsection
