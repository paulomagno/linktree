<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Page;
use App\Models\Link;
use App\Models\View;

class PageController extends Controller
{
    // index
    public function index($slug)
    {
        $page = Page::where('slug',$slug)->first();

        if ($page) {
            // Background;
            $bg = '#FFFFFF';

            switch ($page->op_bg_type) {
                case 'image':
                    $bg = "url('".url('/media/uploads').'/'.$page->op_bg_value."')";
                    break;

                case 'color':
                    $colors = explode(',',$page->op_bg_value);
                    $bg  = 'linear-gradient(90deg,';
                    $bg .= $colors[0].',';
                    $bg .= !empty($colors[1]) ? $colors[1] : $colors[0];
                    $bg .= ')';
                    break;


                default:
                    $bg = '#FFFFFF';
                    break;
            }

            // Links
            $links = Link::where('id_page', $page->id)
                 ->where('status',1)
                 ->orderBy('order')
                 ->get();


            // Registra Visualização
            $view = View::firstOrNew(
                ['id_page' => $page->id, 'view_date' => date('Y-m-d')]
            );

            $view->total ++;
            $view->save();

            return view('page',[
                'fontColor'   => $page->op_font_color,
                'profileImage' => url('/media/uploads').'/'.$page->op_profile_image,
                'title'        => $page->op_title,
                'description'  => $page->op_description,
                'fbPixel'      => $page->op_fb_pixel,
                'bg'           => $bg,
                'links'        => $links
            ]);
        }
        else {
            return view('notfound');
        }
    }
}
