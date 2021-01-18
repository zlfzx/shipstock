@extends('layouts.global')
@section('title', 'Pemakaian')

@section('style')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
@endsection

@section('content')
    <div class="row">
        <div class="col-md-10 col-sm-12">
            <div class="card">
                <div class="card-header border-0">
                  <h3 class="card-title">Daftar Pemakaian</h3>
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
                      <th>Sparepart</th>
                      <th>Kapal</th>
                      <th>Warehouse</th>
                      <th>Jumlah</th>
                      <th>Tanggal Pemakaian</th>
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
            <h5 class="modal-title">Tambah Pemakaian</h5>
              <button class="close">&times;</button>
          </div>
          <form id="formTambah">
          <div class="modal-body">
            <div class="form-group">
              <label for="formSparepart">Sparepart</label>
              <select name="sparepart_id" id="formSparepart" class="form-control select-sparepart"></select>
            </div>
            <div class="form-group">
              <label for="formKapal">Kapal</label>
              <select name="kapal_id" id="formKapal" class="form-control select-kapal"></select>
            </div>
            <div class="form-group">
                <label for="formJumlah">Jumlah</label>
                <input type="number" min="0" max="0" name="jumlah" id="formJumlah" class="form-control form-jumlah" placeholder="Masukkan jumlah pemakaian">
            </div>
            <div class="form-group">
              <label for="formTanggal">Tanggal Pemakaian</label>
              <input type="text" name="tanggal_pemakaian" class="form-control form-tanggal" id="formTanggal">
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
            <h5 class="modal-title">Edit Pemakaian</h5>
              <button class="close">&times;</button>
          </div>
          <form id="formEdit">
          @method('PUT')
              <input type="hidden" name="id" id="editId">
          <div class="modal-body">
            <div class="form-group">
              <label for="editSparepart">Sparepart</label>
              <select name="sparepart_id" id="editSparepart" class="form-control select-sparepart"></select>
            </div>
            <div class="form-group">
              <label for="editKapal">Kapal</label>
              <select name="kapal_id" id="editKapal" class="form-control select-kapal"></select>
            </div>
            <div class="form-group">
                <label for="editJumlah">Jumlah</label>
                <input type="number" min="0" name="jumlah" id="editJumlah" class="form-control form-jumlah" placeholder="Masukkan jumlah pemakaian">
            </div>
            <div class="form-group">
              <label for="editTanggal">Tanggal Pemakaian</label>
              <input type="text" name="tanggal_pemakaian" class="form-control form-tanggal" id="editTanggal">
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
  <script src="{{ asset('plugins/daterangepicker/moment.min.js') }}"></script>
  <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
  <script>
    const status = "{{ auth()->user()->status }}"

    const selectSparepart = $('.select-sparepart').select2({
        theme: 'bootstrap4',
        placeholder: 'Pilih Sparepart...',
        ajax: {
            delay: 250,
            url: "{{ route('sparepart.select') }}",
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
    $('#formSparepart').on('change', function () {
        const id = $(this).val()
        $.get({
            url: "{{ route('sparepart.index') }}/"+id,
            success: function (res) {
                let data = res.data
                $('.form-jumlah').val(0).attr('max', data.stok)
            }
        })
    })

    const selectKapal = $('.select-kapal').select2({
        theme: 'bootstrap4',
        placeholder: 'Pilih Kapal...',
        ajax: {
            delay: 250,
            url: "{{ route('kapal.select') }}",
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

    const formTanggal = $('.form-tanggal').daterangepicker({
        singleDatePicker: true,
        timePicker: true,
        startDate: moment().startOf('hour'),
        endDate: moment().startOf('hour').add(32, 'hour'),
        locale: {
          format: 'YYYY-MM-DD hh:mm:ss'
        }
    })

    const table = $('#table').DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: "{{ route('pemakaian.data') }}"
      },
      columns: [
        {data: 'index'},
        {data: 'sparepart.nama'},
        {data: 'kapal.nama'},
        {data: 'sparepart.warehouse.nama'},
        {data: 'jumlah'},
        {data: 'tanggal_pemakaian'},
        {data: 'id', render: function (data, type, row) {
          if (status === 'admin') {
              return `
                <button class="btn btn-xs btn-warning btn-edit" data-id="${data}" data-sparepart="${row.sparepart_id}" data-sparepartnama="${row.sparepart.nama}" data-sparepartstok="${row.sparepart.stok}" data-kapal="${row.kapal_id}" data-kapalnama="${row.kapal.nama}" data-jumlah="${row.jumlah}" data-tanggal="${row.tanggal_pemakaian}">Edit</button>
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
        url: "{{ route('pemakaian.store') }}",
        success: function (res) {
            table.draw()
            swal.fire('Berhasil', 'Pemakaian berhasil ditambahkan', 'success')
            formTambah.trigger('reset')
            $('#modal-tambah').modal('hide')
        },
        error: function (res) {
            let response = res.responseJSON
            swal.fire('Gagal', response.message, 'error')
        }
      })
    })

    // btn edit
    table.on('click', '.btn-edit', function() {
        const id = $(this).data('id')
        const sparepart_id = $(this).data('sparepart')
        const sparepart_nama = $(this).data('sparepartnama')
        const sparepart_stok = $(this).data('sparepartstok')
        const kapal_id = $(this).data('kapal')
        const kapal_nama = $(this).data('kapalnama')
        const jumlah = $(this).data('jumlah')
        const tanggal = $(this).data('tanggal')

        $('#editId').val(id)
        $('#editSparepart').empty().append('<option value="'+sparepart_id+'">'+sparepart_nama+'</option>').trigger('change')
        $('#editKapal').empty().append('<option value="'+kapal_id+'">'+kapal_nama+'</option>').trigger('change')
        $('#editJumlah').attr('max', sparepart_stok).val(jumlah)
        $('#editTanggal').val(tanggal)
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
            url: "{{ route('pemakaian.index') }}/" + data.get('id'),
            success: function (res) {
                table.draw();
                swal.fire('Berhasil', 'Pemakaian berhasil diperbarui', 'success')
                $('#modal-edit').modal('hide')
            }
        })
    })

    // Hapus
    table.on('click', '.btn-hapus', function() {
        const id = $(this).data('id')
        const nama = $(this).data('nama')
        if (confirm('Hapus data Pemakaian?')) {
            $.ajax({
                type: 'DELETE',
                url: "{{ route('pemakaian.index') }}/" + id,
                success: function (res) {
                    swal.fire('Berhasil', 'Pemakaian berhasil dihapus', 'success')
                    table.draw()
                }
            })
        }
    })
  </script>
@endsection
