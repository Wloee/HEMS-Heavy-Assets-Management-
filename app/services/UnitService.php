<?php
Namespace App\Http\Requests;

class UnitService{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handle($request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'kode_unit' => 'required|string|max:50|unique:unit,kode_unit',
            'nama_unit' => 'required|string|max:255',
            // Add other validation rules as needed
        ]);

        // Process the validated data
        // For example, save to the database or perform other actions

        return response()->json(['message' => 'Unit processed successfully'], 200);
    }

}
