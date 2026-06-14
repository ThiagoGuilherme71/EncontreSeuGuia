<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\FotoAventura;
use App\Support\ImageResizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FotoAventuraController extends Controller
{
    public const MAX_FOTOS_POR_PESSOA = 6;

    /**
     * Publica uma foto da aventura (trilha concluída), respeitando o
     * limite por pessoa.
     */
    public function store(Request $request, $id)
    {
        $request->validate([
            'foto' => 'required|image|max:10240',
            'legenda' => 'nullable|string|max:140',
        ]);

        $agendamento = Agendamento::findOrFail($id);
        [$type, $autorId] = $this->resolveAutor($request, $agendamento);

        abort_unless($agendamento->status === 'completed', 403, 'As fotos são liberadas quando a trilha é concluída.');

        $jaPostadas = FotoAventura::where('agendamento_id', $agendamento->id)
            ->where('postado_por_type', $type)
            ->where('postado_por_id', $autorId)
            ->count();

        if ($jaPostadas >= self::MAX_FOTOS_POR_PESSOA) {
            return back()->with('error', 'Você já postou o máximo de ' . self::MAX_FOTOS_POR_PESSOA . ' fotos nessa aventura.');
        }

        $paths = ImageResizer::save($request->file('foto'), 'aventuras', 1600, 480);

        FotoAventura::create([
            'agendamento_id' => $agendamento->id,
            'postado_por_type' => $type,
            'postado_por_id' => $autorId,
            'path' => $paths['path'],
            'thumb_path' => $paths['thumb_path'],
            'legenda' => $request->legenda,
        ]);

        return back()->with('success', 'Foto postada! 📸');
    }

    /**
     * Remove uma foto, permitido apenas a quem a publicou.
     */
    public function destroy(Request $request, $id)
    {
        $foto = FotoAventura::with('agendamento')->findOrFail($id);
        [$type, $autorId] = $this->resolveAutor($request, $foto->agendamento);

        abort_unless(
            $foto->postado_por_type === $type && $foto->postado_por_id === $autorId,
            403,
            'Você só pode remover as suas fotos.'
        );

        foreach ([$foto->path, $foto->thumb_path] as $path) {
            if ($path) {
                Storage::disk('public')->delete(str_replace('storage/', '', $path));
            }
        }

        $foto->delete();

        return back()->with('success', 'Foto removida.');
    }

    /**
     * Resolve o autor (trilheiro ou guia) a partir do guard autenticado.
     *
     * @return array{0: string, 1: int}
     */
    private function resolveAutor(Request $request, Agendamento $agendamento): array
    {
        $user = $request->user('web');
        $guia = $request->user('guia');

        if ($user && $agendamento->id_users === $user->id) {
            return ['user', $user->id];
        }
        if ($guia && $agendamento->id_guia === $guia->id) {
            return ['guia', $guia->id];
        }

        abort(403);
    }
}
