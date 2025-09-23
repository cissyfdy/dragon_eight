@extends('layout')

@section('konten')
    <title>Profil Klub</title>

    <style>        
        .badge-hari {
            font-size: 0.875rem;
            padding: 0.375rem 0.75rem;
        }
        
        .jadwal-info {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 0.5rem;
            margin: 0.25rem 0;
        }
        
        .time-badge {
            background-color: #e3f2fd;
            color: #1565c0;
            border: 1px solid #bbdefb;
        }
        
        .student-count {
            background-color: #f3e5f5;
            color: #7b1fa2;
            border: 1px solid #e1bee7;
        }
    </style>

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <!-- Header Section -->
                <div class="header-section">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="mb-2">
                                <i class="bi bi-calendar3 me-3"></i>
                                Profil Klub {{ $unit->nama_unit }}
                            </h1>
                            <p class="mb-0 opacity-75"><strong></strong></p>
                            <p class="mb-0 opacity-75"><strong>Alamat:</strong> {{ $unit->alamat }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Map -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card h-100">
                            <div class="card-body p-0">
                                <iframe
                                    width="100%"
                                    height="400"
                                    frameborder="0" 
                                    style="border:0"
                                    src="https://www.google.com/maps?q={{ urlencode($unit->alamat) }}&output=embed"
                                    allowfullscreen>
                                </iframe>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>

                <!-- Daftar Murid Section -->
                <div class="table-container mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                      <h2 class="mb-0"><i class="bi bi-person fs-1 me-2"></i>Daftar Murid {{ $unit->nama_unit }}</h2>
                    </div>
                    
                    @if($murid->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 15%">No Reg</th>
                                        <th style="width: 30%">Nama Murid</th>
                                        <th style="width: 25%">Tingkat Sabuk</th>
                                        <th style="width: 30%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($murid as $data)
                                    <tr>
                                        <td>{{ $data->nomor_register }}</td>
                                        <td>{{ $data->nama_murid }}</td>
                                        <td>{{ $data->tingkat_sabuk }}</td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="{{ route('admin.editMurid', $data->murid_id) }}" 
                                                   class="btn-action btn-edit"
                                                   title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                
                                                <form action="{{ route('murid.delete', ['id' => $data->murid_id]) }}" method="POST" style="display: inline-block;">
                                                    @csrf
                                                    <button class="btn-action btn-delete" 
                                                            onclick="return confirm('Hapus Murid ini?')" 
                                                            title="Hapus Murid">
                                                        <i class="bi bi-trash"></i> 
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="bi bi-person-x"></i>
                            <h4>Tidak Ada Murid</h4>
                            <p>Belum ada murid terdaftar di unit ini.</p>
                        </div>
                    @endif
                </div>

                <!-- Jadwal Section -->
                <div class="table-container mb-4">
                  <div class="d-flex justify-content-between align-items-center mb-4">
                      <h2 class="mb-0"><i class="bi bi-calendar3 me-2"></i>Jadwal Unit {{ $unit->nama_unit }}</h2>
                  </div>
                  
                  @if($jadwal->count() > 0)
                      <div class="table-responsive">
                          <table class="table table-hover mb-0">
                            <thead>
                              <tr>
                                  <th style="width: 15%">Hari</th>
                                  <th style="width: 20%">Jam</th>
                                  <th style="width: 15%">Jumlah Murid</th>
                                  <th style="width: 25%">Pelatih</th>
                                  <th style="width: 25%">Keterangan</th>
                              </tr>
                            </thead>
                            <tbody>
                                @foreach ($jadwal as $j)
                                <tr>
                                    <td>
                                        <span class="badge badge-hari 
                                            @switch($j->hari)
                                                @case('Senin') bg-primary @break
                                                @case('Selasa') bg-success @break
                                                @case('Rabu') bg-info @break
                                                @case('Kamis') bg-warning text-dark @break
                                                @case('Jumat') bg-danger @break
                                                @case('Sabtu') bg-secondary @break
                                                @case('Minggu') bg-dark @break
                                                @default bg-light text-dark @break
                                            @endswitch
                                        ">
                                            {{ $j->hari }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="time-display">
                                            {{ date('H:i', strtotime($j->jam_mulai)) }}<br>
                                            <small>{{ date('H:i', strtotime($j->jam_selesai)) }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $j->jumlah_murid }}
                                        </span>
                                        <small class="text-muted d-block">murid</small>
                                    </td>
                                    <td>
                                        <div class="pelatih-display">
                                            {{ $j->nama_pelatih }}
                                        </div>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $j->keterangan ?? 'Tidak ada keterangan' }}</small>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                          </table>
                      </div>
                  @else
                      <div class="empty-state">
                          <i class="bi bi-calendar-x"></i>
                          <h4>Tidak Ada Jadwal</h4>
                          <p>Belum ada jadwal latihan untuk unit ini.</p>
                      </div>
                  @endif
                </div>

                <!-- Catatan Prestasi Section (Template untuk future development) -->
                <div class="table-container">
                  <div class="d-flex justify-content-between align-items-center mb-4">
                      <h2 class="mb-0"><i class="bi bi-trophy me-2"></i>Catatan Prestasi {{ $unit->nama_unit }}</h2>
                  </div>
                  
                  <div class="empty-state">
                      <i class="bi bi-trophy"></i>
                      <h4>Fitur Dalam Pengembangan</h4>
                      <p>Fitur catatan prestasi akan segera hadir.</p>
                  </div>
                  
                  <!-- Template untuk future development -->
                  <div class="table-responsive" style="display: none;">
                      <table class="table table-hover mb-0">
                        <thead>
                          <tr>
                              <th style="width: 25%">Nama Murid</th>
                              <th style="width: 30%">Kejuaraan</th>
                              <th style="width: 15%">Tahun</th>
                              <th style="width: 15%">Medali</th>
                              <th style="width: 15%">Aksi</th>
                          </tr>
                        </thead>
                        <tbody>
                            <!-- Data prestasi akan ditampilkan di sini -->
                        </tbody>
                      </table>
                  </div>
                </div>
        </div>
    </div>
@endsection