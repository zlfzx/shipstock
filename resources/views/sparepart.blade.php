@extends('layouts.global')
@section('title', 'Sparepart')

@section('style')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('content')
    <div class="row">
        <div class="col-md-10 col-sm-12">
            <div class="card">
                <div class="card-header border-0">
                  <h3 class="card-title">Daftar Sparepart</h3>
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
                      <th>Kode</th>
                      <th>Nama</th>
                      <th>Stok</th>
                      <th>Warehouse</th>
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
            <h5 class="modal-title">Tambah Sparepart</h5>
              <button class="close">&times;</button>
          </div>
          <form id="formTambah">
          <div class="modal-body">
            <div class="form-group">
              <label for="formKode">Kode</label>
              <input type="text" name="kode" class="form-control" id="formKode" placeholder="Masukkan kode sparepart">
            </div>
            <div class="form-group">
              <label for="formNama">Nama</label>
              <input type="text" name="nama" class="form-control" id="formNama" placeholder="Masukkan Nama sparepart">
            </div>
            <div class="form-group">
              <label for="formStok">Stok</label>
              <input type="number" name="stok" id="formStok" min="1" class="form-control" placeholder="Masukkan jumlah sparepart">
            </div>
            <div class="form-group">
              <label for="formWarehouse">Warehouse</label>
              <select name="warehouse_id" id="formWarehouse" class="form-control select-warehouse" placeholder="Pilih sumber warehouse"></select>
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
            <h5 class="modal-title">Edit Sparepart</h5>
              <button class="close">&times;</button>
          </div>
          <form id="formEdit">
          @method('PUT')
          <input type="hidden" name="id" id="editId">
          <div class="modal-body">
            <div class="form-group">
              <label for="editKode">Kode</label>
              <input type="text" name="kode" class="form-control" id="editKode" placeholder="Masukkan kode sparepart">
            </div>
            <div class="form-group">
              <label for="editNama">Nama</label>
              <input type="text" name="nama" class="form-control" id="editNama" placeholder="Masukkan Nama sparepart">
            </div>
            <div class="form-group">
              <label for="editStok">Stok</label>
              <input type="number" name="stok" id="editStok" min="1" class="form-control" placeholder="Masukkan jumlah sparepart">
            </div>
            <div class="form-group">
              <label for="editWarehouse">Warehouse</label>
              <select name="warehouse_id" id="editWarehouse" class="form-control select-warehouse" placeholder="Pilih sumber warehouse"></select>
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
  <script src="{{ asset('plugins/select2/js/select2.js') }}"></script>
  <script>
    const status = "{{ auth()->user()->status }}"
    const table = $('#table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('sparepart.data') }}"
        },
        columns: [
            {data: 'index'},
            {data: 'kode'},
            {data: 'nama'},
            {data: 'stok'},
            {data: 'warehouse.nama'},
            {
                data: 'id', render: function (data, type, row) {
                    if (status === 'admin') {
                        return `<button class="btn btn-xs btn-warning btn-edit" data-id="${data}" data-kode="${row.kode}" data-nama="${row.nama}" data-stok="${row.stok}" data-warehouse="${row.warehouse_id}" data-warehousenama="${row.warehouse.nama}">Edit</button>
                               <button class="btn btn-xs btn-danger btn-hapus" data-id="${data}">Hapus</button>`
                    } else {
                        return '-'
                    }
                }
            },
        ]
    })

    // select warehouse
    const selectWarehouse = $('.select-warehouse').select2({
        theme: 'bootstrap4',
        placeholder: 'Cari Warehouse...',
        ajax: {
            delay: 250,
            url: "{{ route('warehouse.select') }}",
            processResults: function(data, params) {
                params.page = params.page || 1
                // console.log(data)
                return {
                    results: $.map(data, function(item) {
                        return {
                            id: item.id,
                            text: item.nama
                        }
                    }),
                    pagination: {
                        more: (params.page * 10) < data.length
                    }
                }
            }
        }
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
        url: "{{ route('sparepart.store') }}",
        success: function (res) {
            table.draw()
            swal.fire('Berhasil', 'Sparepart berhasil ditambahkan', 'success')
            formTambah.trigger('reset')
            $('#modal-tambah').modal('hide')
        }
      })
    })

    // btn edit
    table.on('click', '.btn-edit', function() {
        const id = $(this).data('id')
        const kode = $(this).data('kode')
        const nama = $(this).data('nama')
        const stok = $(this).data('stok')
        const warehouse_id = $(this).data('warehouse')
        const warehouse_nama = $(this).data('warehousenama')

        $('#editId').val(id)
        $('#editKode').val(kode)
        $('#editNama').val(nama)
        $('#editStok').val(stok)
        $('#editWarehouse').empty().append('<option value="'+warehouse_id+'">'+warehouse_nama+'</option>').trigger('change')
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
            url: "{{ route('sparepart.index') }}/" + data.get('id'),
            success: function (res) {
                table.draw();
                swal.fire('Berhasil', 'Sparepart berhasil diperbarui', 'success')
                $('#modal-edit').modal('hide')
            }
        })
    })

    // Hapus
    table.on('click', '.btn-hapus', function() {
        const id = $(this).data('id')
        if (confirm('Hapus data Sparepart?')) {
            $.ajax({
                type: 'DELETE',
                url: "{{ route('sparepart.index') }}/" + id,
                success: function (res) {
                    swal.fire('Berhasil', 'Warehouse berhasil dihapus', 'success')
                    table.draw()
                }
            })
        }
    })
  </script>
@endsection
