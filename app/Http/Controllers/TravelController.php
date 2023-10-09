<?php

namespace App\Http\Controllers;

use App\Models\Travel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\TravelsImport;
use Maatwebsite\Excel\Facades\Excel;


class TravelController extends Controller
{

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

        $this->validate($request,['document'=> ['required','max:5120','mimes:xlsx']],$mesagges);

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
}
