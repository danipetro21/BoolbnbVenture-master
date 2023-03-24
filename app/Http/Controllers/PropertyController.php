<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Sponsorship;
use App\Models\Message;
use App\Models\View;
use App\Services\PropertyService;
use Inertia\Inertia;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class PropertyController extends Controller
{

  // Serve per creare la proprieta'
  protected $propertyService;
  public function __construct(PropertyService $propertyService)
  {
    $this->propertyService = $propertyService;
  }


  public function propertyShow($id)
  {
    $property = Property::with('services')->findOrFail($id);

    $ip = request()->ip();
    $today = now()->format('Y-m-d');

    // Check if the user has visited this property 3 times in the last 24 hours
    $views = View::where('property_id', $id)
      ->where('ip', $ip)
      ->where('visit_date', '>=', now()->subDay())
      ->count();

    if ($views <= 3) {
      // Save a new view for this property
      $view = new View([
        'ip' => $ip,
        'visit_date' => $today,
      ]);
      $property->views()->save($view);
    }

    return Inertia::render('Properties/PropertyShow', [
      'property' => $property
    ]);
  }


  // RESTITUISCE I SERVIZI DI UNA PROPRIETA'
  public function getPropertyServices($id)
  {
    $property = Property::with('services')->findOrFail($id);
    $services = $property->services->pluck('id')->toArray();

    return new JsonResponse([
      'success' => true,
      'response' => $services,
    ]);
  }


  public function getProperties()
  {
    // $properties = Property::all();
    $propertiesWithSponsorship = Property::whereHas('sponsorships')->orderBy('id')->paginate(9);
    $propertiesWithoutSponsorship = Property::whereDoesntHave('sponsorships')->orderBy('id')->paginate(9);

    $properties = $propertiesWithSponsorship->merge($propertiesWithoutSponsorship);

    return response()->json([
      'success' => true,
      'response' => $properties,
    ]);
  }

  //prende solo le proprietà con sponsorship associata
  public function getSponsorProperties()
  {
    // $properties = Property::all();
    $properties = Property::whereHas('sponsorships')->orderBy('id')->get();

    return response()->json([
      'success' => true,
      'response' => $properties,
    ]);
  }

  // prende le sponsorship
  public function getSponsorships()
  {
    $sponsorship = Sponsorship::all();
    return response()->json([
      'success' => true,
      'response' => $sponsorship,
    ]);
  }

  //associa la sponsorship alla proprietà
  public function buySponsorship(Request $request)
  {
    $property = Property::find($request->input('property_id'));
    $sponsorship = Sponsorship::find($request->input('sponsorship_id'));

    // Imposta la data di inizio alla data corrente
    $start_date = now()->format('Y-m-d');

    // Ottieni la durata della sponsorship e calcola la data di fine
    $duration = explode(':', $sponsorship->duration);
    $end_date = now()->addHours($duration[0])->format('Y-m-d');

    // Associa la sponsorship alla proprietà con le date di inizio e fine impostate
    $property->sponsorships()->attach($sponsorship->id, [
      'start_date' => $start_date,
      'end_date' => $end_date,
    ]);

    return response()->json([
      'success' => true,
      'message' => 'Sponsorship successfully bought - Sponsorship id ' . $sponsorship->id,
    ]);
  }

  // RESTITUISCE LE SPONSORSHIPS DI UNA PROPRIETA'
  // public function getPropertySponsorships($property_id)
  // {
  //   $sponsorships = Sponsorship::join('property_sponsorship', 'sponsorships.id', '=', 'property_sponsorship.sponsorship_id')
  //     ->where('property_sponsorship.property_id', $property_id)
  //     ->get();
  //   return response()->json([
  //     'success' => true,
  //     'response' => $sponsorships,
  //   ]);
  // }

  // RESTITUISCE I MESSAGGI DI UNA PROPRIETA'
  public function getPropertyMessages($property_id)
  {
    $messages = Message::where('property_id', $property_id)->get();
    return response()->json([
      'success' => true,
      'response' => $messages,
    ]);
  }

  // salvare messaggi nel database
  public function storeMessage(Request $request, $propertyId)
  {
    $validatedData = $request->validate([
      'email' => 'required|email',
      'message' => 'required',
    ]);

    $property = Property::findOrFail($propertyId);
    $property->messages()->create($validatedData);

    return redirect()->back();
  }


  public function destroyMessage(Message $message)
  {

    $message->delete();

    return redirect()->back()
      ->with('message', 'Message deleted');
  }


  // STATSSS

  // month

  // public function getStats($propertyId)
  // {
  //   $stats = DB::table('views')
  //     ->select(DB::raw('MONTH(visit_date) as month'), DB::raw('count(*) as views'))
  //     ->where('property_id', '=', $propertyId)
  //     ->groupBy('month')
  //     ->get();

  //   if ($stats->isEmpty()) {
  //     return response()->json(['message' => 'Nessuna statistica disponibile per questa proprietà']);
  //   }

  //   $months = [
  //     '01' => 'January',
  //     '02' => 'February',
  //     '03' => 'March',
  //     '04' => 'April',
  //     '05' => 'May',
  //     '06' => 'June',
  //     '07' => 'July',
  //     '08' => 'August',
  //     '09' => 'September',
  //     '10' => 'October',
  //     '11' => 'November',
  //     '12' => 'December',
  //   ];

  //   $data = [];
  //   foreach ($stats as $stat) {
  //     $month = $months[str_pad($stat->month, 2, "0", STR_PAD_LEFT)];
  //     $views = $stat->views;
  //     $data[] = ['month' => $month, 'views' => $views];
  //   }

  //   return response()->json(['data' => $data]);
  // }

  // day by day

  // public function getStats($propertyId)
  // {
  //   $stats = DB::table('views')->select(DB::raw('DATE(visit_date) as date'), DB::raw('count(*) as views'))->where('property_id', '=', $propertyId)->groupBy('date')->get();
  //   if ($stats->isEmpty()) {
  //     return response()->json(['message' => 'Nessuna statistica disponibile per questa proprietà']);
  //   }
  //   $data = [];
  //   foreach ($stats as $stat) {
  //     $date = date('Y-m-d', strtotime($stat->date));
  //     $views = $stat->views;
  //     $data[] = ['date' => $date, 'views' => $views];
  //   }
  //   return response()->json(['data' => $data]);
  // }

  // last 15 days

  public function getStats($propertyId)
  {
    $stats = DB::table('views')->select(DB::raw('DATE(visit_date) as date'), DB::raw('count(*) as views'))->where('property_id', '=', $propertyId)->where('visit_date', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL 15 DAY)'))->groupBy('date')->get();
    if ($stats->isEmpty()) {
      return response()->json(['message' => 'No statistics for this property']);
    }
    $data = [];
    foreach ($stats as $stat) {
      $date = date('Y-m-d', strtotime($stat->date));
      $views = $stat->views;
      $data[] = ['date' => $date, 'views' => $views];
    }
    return response()->json(['data' => $data]);
  }
}