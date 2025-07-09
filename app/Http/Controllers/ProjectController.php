<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\StoreToolRequest;
use App\Http\Requests\StoreToolProjectRequest;
use App\Models\ProjectTool;
use App\Models\Tool;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\ProjectApplicant;



class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         /** @var \App\Models\User $user */
        //
        $user = Auth::user();

        $projectsQuery = Project::with(['category', 'applicants'])->orderByDesc('id');

        if($user->hasRole('project_client')) {
            // filtering data projek berdasarkan dari client id== user id
            $projectsQuery->whereHas('owner', function ($query) use ($user) {
                $query->where('client_id', $user->id);
            });
            }
        $projects = $projectsQuery->paginate(10);

        return view('admin.projects.index', compact('projects'));

    } // fungsi untuk menampilkan data project yang telah dibuat oleh user

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // function untuk menampilkan form create project
        // mengambil semua kategori untuk ditampilkan di form
        $categories = Category::all();
        return view('admin.projects.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        //
        $user = Auth::user();
        $balance = $user->wallet->balance;

        if ($request->input('budget') > $balance) {
            return redirect()->back()->withErrors(
                ['budget' => 'Budget tidak cukup untuk membuat projek.']
            );
        }

        DB::transaction(function () use ($request, $user) {

            // mengurangi saldo wallet klien
            $user->wallet->decrement('balance', $request->input('budget'));

            $projectWalletTransaction = WalletTransaction::create([
                'user_id' => $user->id,
                'type' => 'Project Cost',
                'amount' => -$request->input('budget'),
                'is_paid' => true,
            ]);

            $validated = $request->validated();

            if($request->hasFile('thumbnail')) {
                $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
                $validated['thumbnail'] = $thumbnailPath;
            }

            $validated['slug'] = Str::slug($validated['name']);
            $validated['has_finished'] = false;
            $validated['has_started'] = false;
            $validated['client_id'] = $user->id;

            $newProject = Project::create($validated);

        });
        return redirect()->route('admin.projects.index');
    } // fungsi untuk menyimpan data project baru

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        //
        return view('admin.projects.show', compact('project'));
    } // fungsi untuk menampilkan detail project

    public function tools(Project $project)
    {
        if ($project->client_id !== auth()->id()) {
            abort(403, 'You are not authorized');
        }

        $tools = Tool::all();
        // Menampilkan halaman tools untuk project tertentu
        return view('admin.projects.tools', compact('project', 'tools'));
    } // fungsi untuk menampilkan halaman tools pada project tertentu

    public function tools_store(StoreToolProjectRequest $request, Project $project) {

        DB::transaction(function() use ($request, $project) {

            $validated = $request->validated();
            $validated['project_id'] = $project->id;

            $toolProject = ProjectTool::firstOrCreate($validated);
        });

        return redirect()->route('admin.projects.tools', $project->id);
    } // fungsi untuk menyimpan data tools pada project tertentu

    public function complete_project_store(ProjectApplicant $projectApplicant)
    {

        DB::transaction(function() use ($projectApplicant) {

            $validated['type'] = 'Revenue';
            $validated['is_paid'] = 'true';
            $validated['amount'] = $projectApplicant->project->budget;
            $validated['user_id'] = $projectApplicant->freelancer_id;

            $addRevenue = WalletTransaction::create($validated);

            $projectApplicant->freelancer->wallet->increment('balance', $projectApplicant->project->budget);

            $projectApplicant->project->update([
                'has_finished' => true,
            ]);

        });

        return redirect()->route('admin.projects.show', [$projectApplicant->project, $projectApplicant->id]);

    } // fungsi untuk menyimpan data project yang telah selesai
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        //
    }
}
