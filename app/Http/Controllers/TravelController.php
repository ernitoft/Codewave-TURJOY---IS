<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use App\Models\travel;
use Illuminate\Http\Request;
use App\Imports\TravelsImports;
use Maatwebsite\Excel\Facades\Excel;
use Maatsite\Excel\Validatos\ValidationException;
=======
use App\Models\Travel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\TravelsImport;
use Maatwebsite\Excel\Facades\Excel;


>>>>>>> 1344912003c08a7dfcd20de5619ccd4b8bb13019

class TravelController extends Controller
{

<<<<<<< HEAD
    

        public function indexAddTravels()
        {
            if(session('validRows')  || session('invalidRows') || session('duplicateRows')) {
                session()->put('validRows', []);
                session()->put('invalidRows', []);
                session()->put('duplicateRows', []);
            } else{
                session(['validRows' => []]);
                session(['invalidRows' => []]);
                session(['duplicateRows' => []]);
            }

            return view('admin.travel.index', [
                'validRows' => session('validRows'),
                'invalidRows' => session('invalidRows'),
                'duplicateRows' => session('duplicateRows')
            ]);
        }
        
        public function indexTravels()
        {
            return view('admin.travel.index', [
                'validRows' => session('validRows'),
                'invalidRows' => session('invalidRows'),
                'duplicateRows' => session('duplicateRows')
            ]);
        }

        public function travelCheck(Request $request)
        {
            $messages = makeMessages();

            $this->validate($request, [
                'document' => ['required', 'max:5120', 'mimes:xlsx'],
            ],  $messages);

            if($request->hasFile('document')) {
                $file = request()->file('document');

                $import = new TravelsImport();
                Excel::import($import, $file);

                $validRows = $import->getValidRows();
                $invalidRows = $import->getInvalidRows();
                $duplicatedRows = $import->getDuplicatedRows();

                

                foreach($validRows as $row){
                    $origin = $row['origen'];
                    $destination = $row['destino'];

                    $travel = Travel::where('origin', $origin)
                        ->where('destination', $destination)
                        ->first();

                    if($travel) {
                        
                        $travel->update([
                            'seat_quantity' => $row['cantidad_de_asientos'],
                            'base_rate' => $row['tarifa_base'],
                        ]);
                        $travel->save();
                    }else{
                        Travel::create([
                            'origin' =>$origin,
                            'destination' =>$destination,
                            'seat_quantity' =>$row['cantidad_de_asientos'],
                            'base_rate' =>$row['tarifa_base'],
                        ]);
                    }
                }

                $invalidRows = array_filter($invalidRows, function ($invalidrow) {
                    return $invalidrow['origen'] !== null || $invalidrow['destino'] !== null || $invalidrow['cantidad_de_asientos'] !== null  || $invalidrow['tarifa_base'] !== null;
                    
                });
                session()->put('validRows', $validRows);
                session()->put('invalidRows', $invalidRows);
                session()->put('duplicatedRows', $duplicatedRows);

                return redirect()->route('travelsAdd.index');
            }
        }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(travel $travel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(travel $travel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, travel $travel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(travel $travel)
    {
        //
    }
}
=======
    public function indexAddTravels(){

        if(session('validRows') || session('invalidRows') || session('duplicatedRows')){
            session()->put('validRows',[]);
            session()->put('invalidRows',[]);
            session()->put('duplicatedRows',[]);
        }else{
            session(['validRows'=> []]);
            session(['invalidRows'=> []]);
            session(['duplicatedRows'=> []]);
        }

        return view('index',[
            'validRows'=> session('validRows'),
            'invalidRows'=> session('invalidRows'),
            'duplicatedRows'=> session('duplicatedRows')
        ]);
    }

    public function indexTravels(){

        return view('index',[
            'validRows'=> session('validRows'),
            'invalidRows'=> session('invalidRows'),
            'duplicatedRows'=> session('duplicatedRows')
        ]);
    }

    public function travelCheck(Request $request){

        $mesagges = makeMessages();

        $this->validate($request,['document'=> ['required','max:5120','mimes:xlsx','filled']],$mesagges);

        if($request->hasFile('document')){
            $file = request()->file('document');

            $import = new TravelsImport();
            Excel::import($import,$file);

            $validRows = $import->getValidRows();
            $invalidRows = $import->getInvalidRows();
            $duplicatedRows = $import->getDuplicatedRows();

            foreach($validRows as $row){
                $origin = $row['origen'];
                $destination = $row['destino'];

                $travel = Travel::where('origin',$origin)->where('destination',$destination)->first();

                if($travel){

                    $travel->update([
                        'seat_quantity'=> $row['cantidad_de_asientos'],
                    'base_rate'=> $row['tarifa_base']]);
                }else{

                    Travel::create([
                        'origin'=>$origin,
                        'destination' => $destination,
                        'seat_quantity' => $row['cantidad_de_asientos'],
                        'base_rate' => $row['tarifa_base']
                    ]);
                }

            }


            $invalidRows = array_filter($invalidRows,function($invalidrow){
                return $invalidrow['origen'] !== null || $invalidrow['destino'] !== null || $invalidrow['cantidad_de_asientos'] !== null || $invalidrow['tarifa_base'] !== null;
            });


            session()->put('validRows',$validRows);
            session()->put('invalidRows',$invalidRows);
            session()->put('duplicatedRows',$duplicatedRows);

            return redirect()->route('travelsAdd.index');

        }
    }
    public function homeIndex(){

        $travels = Travel::get()->count();

        return view('home',[
            'countTravels'=> $travels,
        ]);
    }
}

>>>>>>> 1344912003c08a7dfcd20de5619ccd4b8bb13019
