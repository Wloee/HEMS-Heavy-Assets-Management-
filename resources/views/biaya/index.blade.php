@extends('layouts.app')

@section('content')
<style>
    .form-container {
        max-width: 800px;
        margin: 40px auto;
        background: #fff;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    h3 {
        margin-bottom: 20px;
        font-size: 20px;
        font-weight: 600;
    }
    .form-group {
        margin-bottom: 15px;
    }
    label {
        font-weight: 500;
        margin-bottom: 6px;
        display: block;
    }
    input[type="text"], input[type="number"], input[type="file"] {
        width: 100%;
        padding: 10px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 6px;
    }
    .form-row {
        display: flex;
        gap: 15px;
    }
    .form-row .form-group {
        flex: 1;
    }
    button {
        background-color: #28a745;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        font-size: 14px;
        cursor: pointer;
    }
    button:hover {
        background-color: #218838;
    }
    .required {
        color: red;
    }
</style>

<div class="form-container">
    <h3>Input Biaya Service / Maintenance</h3>

    <form action="" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="nama_item">Nama Item <span class="required">*</span></label>
            <input type="text" name="nama_item" id="nama_item" placeholder="Biaya untuk" required>
        </div>

        <div class="form-group">
            <label for="actual_cost">Actual (cost) <span class="required">*</span></label>
            <input type="number" name="actual_cost" id="actual_cost" placeholder="Rp 134" required>
        </div>

        <div class="form-group">
            <label for="plan_cost">Plan (cost) <span class="required">*</span></label>
            <input type="number" name="plan_cost" id="plan_cost" placeholder="Rp 134" required>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="deviasi">% Deviasi <span class="required">*</span></label>
                <input type="number" name="deviasi" id="deviasi" value="0" required>
            </div>
            <div class="form-group">
                <label for="bukti">Upload Bukti Pembayaran <span class="required">*</span></label>
                <input type="file" name="bukti" id="bukti" accept="image/*" required>
            </div>
        </div>

        <button type="submit">Simpan</button>
    </form>
</div>
<div class="form-container">
    <h3>Input Biaya Service / Maintenance</h3>

    <form action="" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="nama_item">Nama Item <span class="required">*</span></label>
            <input type="text" name="nama_item" id="nama_item" placeholder="Biaya untuk" required>
        </div>

        <div class="form-group">
            <label for="actual_cost">Actual (cost) <span class="required">*</span></label>
            <input type="number" name="actual_cost" id="actual_cost" placeholder="Rp 134" required>
        </div>

        <div class="form-group">
            <label for="plan_cost">Plan (cost) <span class="required">*</span></label>
            <input type="number" name="plan_cost" id="plan_cost" placeholder="Rp 134" required>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="deviasi">% Deviasi <span class="required">*</span></label>
                <input type="number" name="deviasi" id="deviasi" value="0" required>
            </div>
            <div class="form-group">
                <label for="bukti">Upload Bukti Pembayaran <span class="required">*</span></label>
                <input type="file" name="bukti" id="bukti" accept="image/*" required>
            </div>
        </div>

        <button type="submit">Simpan</button>
    </form>
</div>
@endsection
