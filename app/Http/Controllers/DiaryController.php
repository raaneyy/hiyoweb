<?php

namespace App\Http\Controllers;

use App\Models\Diary;
use App\Models\Page;
use Illuminate\Http\Request;

class DiaryController extends Controller
{

    public function show(Request $request, $id)
    {
        $diary = Diary::with(['pages.items'])->findOrFail($id);
        $allDiaries = Diary::select('id', 'title')->get();

        // Get total pages and max page number
        $maxPageNumber = $diary->pages->max('page_number') ?? 2;
        if ($maxPageNumber % 2 !== 0) {
            $maxPageNumber++;
        }

        $totalSpreads = max(1, (int) ceil($maxPageNumber / 2));

        // Get requested spread index (1-based) or calculate from requested page_number
        $spread = (int) $request->query('spread', 1);
        if ($request->has('page')) {
            $pageReq = (int) $request->query('page');
            $spread = (int) ceil($pageReq / 2);
        }

        if ($spread < 1) {
            $spread = 1;
        }
        if ($spread > $totalSpreads) {
            $spread = $totalSpreads;
        }

        $leftPageNum = ($spread * 2) - 1;
        $rightPageNum = $spread * 2;

        $leftPage = $diary->pages->firstWhere('page_number', $leftPageNum);
        $rightPage = $diary->pages->firstWhere('page_number', $rightPageNum);

        return view('diary.show', compact(
            'diary',
            'allDiaries',
            'spread',
            'totalSpreads',
            'leftPageNum',
            'rightPageNum',
            'leftPage',
            'rightPage'
        ));
    }
}
