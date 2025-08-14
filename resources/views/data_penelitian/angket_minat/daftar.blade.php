@extends('layouts.app')

@section('title', 'Edit Angket Motivasi')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Daftar Pertanyaan</div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Pertanyaan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td>1</td><td>Saya memilih keinginan untuk diterima di jurusan ini</td></tr>
                                <tr><td>2</td><td>Saya sadar memilih jurusan ini sangat penting untuk masa depan saya</td></tr>
                                <tr><td>3</td><td>Saya mencari informasi mengenai jurusan ini dari berbagai sumber</td></tr>
                                <tr><td>4</td><td>Saya yakin jurusan ini sesuai dengan kemampuan saya</td></tr>
                                <tr><td>5</td><td>Jurusan ini adalah jurusan yang tepat untuk saya</td></tr>
                                <tr><td>6</td><td>Saya merasa percaya diri untuk bersaing dengan teman-teman di jurusan ini</td></tr>
                                <tr><td>7</td><td>Saya yakin dapat memperoleh hasil belajar yang bagus di jurusan ini</td></tr>
                                <tr><td>8</td><td>Saya merasa senang apabila diterima di jurusan yang saya inginkan</td></tr>
                                <tr><td>9</td><td>Saya memilih jurusan ini karena tertarik untuk mempelajari ilmu yang berkaitan dengan jurusan tersebut</td></tr>
                                <tr><td>10</td><td>Saya lebih menyukai jurusan ini dibandingkan dengan jurusan lain</td></tr>
                                <tr><td>11</td><td>Saya memilih jurusan berdasarkan keinginan sendiri</td></tr>
                                <tr><td>12</td><td>Kesuksesan orang lain di lingkungan sekitar membuat saya tertarik untuk masuk jurusan ini</td></tr>
                                <tr><td>13</td><td>Jurusan yang saya pilih ini adalah salah satu jurusan favorit di sekolah saya</td></tr>
                                <tr><td>14</td><td>Jurusan yang saya pilih ini banyak peminatnya</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
