<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        try {
            $token = Session::get('token');

            $filters = [
                'status' => $request->get('status', ''),
                'date' => $request->get('date', '')
            ];

            // Only make API call if we have a token
            if ($token) {
                $response = Http::withToken($token)
                    ->acceptJson()
                    ->get(route('api.posts'), $filters);

                if ($response->successful()) {
                    $posts = $response->json();
                } else {
                    $posts = [];
                }
            } else {
                $posts = [];
            }

            return view('dashboard', [
                'posts' => $posts,
                'filters' => $filters
            ]);
        } catch (\Exception $e) {
            return view('dashboard', [
                'posts' => [],
                'filters' => []
            ]);
        }
    }
}
