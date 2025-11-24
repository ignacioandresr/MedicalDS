<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportTicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $user = Auth::user();
            // Bloquear acceso a visitantes
            if (method_exists($user, 'hasRole') && $user->hasRole('visitor')) {
                abort(403, 'Acceso no autorizado');
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        if (method_exists($user, 'hasRole') && $user->hasRole('admin')) {
            // Administradores ven todos los tickets
            $tickets = SupportTicket::with('user')
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        } else {
            // Usuarios normales solo ven sus propios tickets
            $tickets = SupportTicket::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        }

        return view('support.index', compact('tickets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('support.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:baja,media,alta,urgente',
        ], [
            'subject.required' => 'El asunto es obligatorio',
            'description.required' => 'La descripción es obligatoria',
            'priority.required' => 'La prioridad es obligatoria',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'abierto';

        SupportTicket::create($validated);

        return redirect()->route('support.index')
            ->with('success', 'Ticket de soporte creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(SupportTicket $support)
    {
        $user = Auth::user();
        $ticket = $support;
        
        // Verificar permisos: admin puede ver todo, usuario solo sus tickets
        if (!method_exists($user, 'hasRole') || !$user->hasRole('admin')) {
            if ($ticket->user_id !== $user->id) {
                abort(403, 'No tienes permiso para ver este ticket');
            }
        }

        return view('support.show', compact('ticket'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SupportTicket $support)
    {
        $user = Auth::user();
        $ticket = $support;
        
        // Solo el creador o admin puede editar
        if (!method_exists($user, 'hasRole') || !$user->hasRole('admin')) {
            if ($ticket->user_id !== $user->id) {
                abort(403, 'No tienes permiso para editar este ticket');
            }
        }

        return view('support.edit', compact('ticket'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SupportTicket $support)
    {
        $user = Auth::user();
        $ticket = $support;
        
        // Verificar permisos
        $isAdmin = method_exists($user, 'hasRole') && $user->hasRole('admin');
        if (!$isAdmin && $ticket->user_id !== $user->id) {
            abort(403, 'No tienes permiso para actualizar este ticket');
        }

        if ($isAdmin) {
            // Admin puede actualizar todo
            $validated = $request->validate([
                'subject' => 'required|string|max:255',
                'description' => 'required|string',
                'status' => 'required|in:abierto,en_progreso,resuelto,cerrado',
                'priority' => 'required|in:baja,media,alta,urgente',
                'admin_response' => 'nullable|string',
            ]);

            if ($request->status === 'resuelto' && $ticket->status !== 'resuelto') {
                $validated['resolved_at'] = now();
            }

            $ticket->update($validated);
        } else {
            // Usuario normal puede actualizar su ticket
            $validated = $request->validate([
                'subject' => 'required|string|max:255',
                'description' => 'required|string',
                'priority' => 'required|in:baja,media,alta,urgente',
            ]);

            $ticket->update($validated);
        }

        return redirect()->route('support.show', $ticket)
            ->with('success', 'Ticket actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SupportTicket $support)
    {
        $user = Auth::user();
        $ticket = $support;
        
        // Solo administradores pueden eliminar tickets
        if (!method_exists($user, 'hasRole') || !$user->hasRole('admin')) {
            abort(403, 'Solo los administradores pueden eliminar tickets');
        }

        $ticket->delete();

        return redirect()->route('support.index')
            ->with('success', 'Ticket eliminado exitosamente');
    }
}
