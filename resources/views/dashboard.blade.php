@extends('layouts.backEnd.main')

@section('content')
    <div class="col-lg-6 col-12">
        <!-- small box -->
        <div class="small-box bg-info">
            <div class="inner">
                <h3>SBS</h3>

                <p>Tanaman Sayuran Dan Buah-Buahan Semusim</p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <a href="{{ url($urlsbs) }}" class="small-box-footer">{{ $kalimat }} <i
                    class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-6 col-12">
        <!-- small box -->
        <div class="small-box bg-success">
            <div class="inner">
                <h3>TBF</h3>

                <p>Tanaman Biofarmaka</p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
            <a href="{{ url($urltbf) }}" class="small-box-footer">{{ $kalimat }} <i
                    class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-6 col-12">
        <!-- small box -->
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>BST</h3>

                <p>Tanaman Buah-Buahan dan Sayuran Tahunan</p>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
            <a href="{{ url($urlbst) }}" class="small-box-footer">{{ $kalimat }} <i
                    class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-6 col-12">
        <!-- small box -->
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>TH</h3>

                <p>Tanaman Hias</p>
            </div>
            <div class="icon">
                <i class="ion ion-pie-graph"></i>
            </div>
            <a href="{{ url($urlth) }}" class="small-box-footer">{{ $kalimat }} <i
                    class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
@endsection
