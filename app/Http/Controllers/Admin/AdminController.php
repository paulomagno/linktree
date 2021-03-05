<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

use App\Models\User;
use App\Models\Page;
use App\Models\Link;

class AdminController extends Controller
{
    public function __construct() {
        $this->middleware('auth', ['except' => [
            'login',
            'loginAction',
            'register',
            'registerAction'
        ]]);
    }

    // Login Screen User
    public function login(Request $request) {
        return view('admin/login',[
            'error' => $request->session()->get('error')
        ]);
    }

    // Action Login Screen User
    public function loginAction(Request $request) {

        $credencials = $request->only('email','password');

        if( Auth::attempt($credencials)) {
            return redirect('/admin');
        } else {
            $request->session()->flash('error','E-mail e/ou senha não conferem');
            return redirect('/admin/login');
        }

    }


    // Register Screen user
    public function register(Request $request) {
        return view('admin/register');

    }

    // Action Register Screen User
    public function registerAction(Request $request) {

        // Receive data user
        $credencials = $request->only('email','password');

        $request->validate([
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        // check if email already exists
        $hasEmail = User::where('email',$credencials['email'])->count();

        if ($hasEmail == 0) {
            // Create user
            $newUser = new User();
            $newUser->email = $credencials['email'];
            $newUser->password = password_hash($credencials['password'],PASSWORD_DEFAULT);
            $newUser->save();

            Auth::login($newUser);
            return redirect('/admin');

        }
    }

    // Logout
    public function logout() {
        Auth::logout();
        return redirect('/admin');
    }

     // Home Screen - Admin
     public function index() {

        $user  = Auth::user();
        $pages = Page::where('id_user',$user->id)->get();

        return view('admin/index',[
            'pages' => $pages
        ]);
    }

    // List Page Link
    public function pageLinks($slug) {

        $user = Auth::user();

        $page = Page::where('slug',$slug)
            ->where('id_user',$user->id)
            ->first();

        if($page) {

            $links = Link::where('id_page',$page->id)
                        ->orderBy('order','ASC')
                        ->get();


            return view('admin/page_links',[
                'menu'  => 'links',
                'page'  => $page,
                'links' => $links
            ]);
        } else {
            return redirect('/admin');
        }

    }
    // Screen New Link
    public function newLink($slug) {
        $user = Auth::user();
        $page = Page::where('id_user',$user->id)
                ->where('slug',$slug)
                ->first();

        if($page) {
            return view('admin/page_editlink',[
                'menu' => 'links',
                'page' => $page
            ]);
        } else {
            return redirect('/admin');
        }
    }

    // Action register New Link
    public function newLinkAction($slug, Request $request) {
        $user = Auth::user();
        $page = Page::where('id_user',$user->id)
                    ->where('slug',$slug)
                    ->first();

        if($page) {

            $fields = $request->validate([
                'status'         => ['required','boolean'],
                'title'          => ['required','min:2'],
                'href'           => ['required','url'],
                'op_bg_color'    => ['required','regex:/^[#][0-9A-F]{3,6}$/i'],
                'op_text_color'  => ['required','regex:/^[#][0-9A-F]{3,6}$/i'],
                'op_border_type' => ['required',Rule::in('square','rounded')]
            ]);

            $totalLinks = Link::where('id_page',$page->id)->count();

            $newLink = new Link();
            $newLink->id_page = $page->id;
            $newLink->status  = $fields['status'];
            $newLink->order   = $totalLinks;
            $newLink->title   = $fields['title'];
            $newLink->href    = $fields['href'];
            $newLink->op_bg_color   = $fields['op_bg_color'];
            $newLink->op_text_color = $fields['op_text_color'];
            $newLink->op_border_type = $fields['op_border_type'];
            $newLink->save();

            return redirect('/admin/'.$page->slug.'/links');


        } else {
            return redirect('/admin');
        }
    }

    // Action edit link
    public function editLink ($slug,$linkid) {

        $user = Auth::user();
        $page = Page::where('id_user',$user->id)
                    ->where('slug',$slug)
                    ->first();


        if($page) {
            $link = Link::where('id_page',$page->id)
                     ->where('id',$linkid)
                     ->first();

            if($link) {
                return view('admin/page_editlink',[
                    'menu' => 'links',
                    'page' => $page,
                    'link' => $link
                ]);
            }
        }

        return redirect('/admin');
    }

    // Action edit link
    public function editLinkAction ($slug,$linkid,Request $request) {
        $user = Auth::user();
        $page = Page::where('id_user',$user->id)
                    ->where('slug',$slug)
                    ->first();


        if($page) {
            $link = Link::where('id_page',$page->id)
                     ->where('id',$linkid)
                     ->first();

            if($link) {

                $fields = $request->validate([
                    'status'         => ['required','boolean'],
                    'title'          => ['required','min:2'],
                    'href'           => ['required','url'],
                    'op_bg_color'    => ['required','regex:/^[#][0-9A-F]{3,6}$/i'],
                    'op_text_color'  => ['required','regex:/^[#][0-9A-F]{3,6}$/i'],
                    'op_border_type' => ['required',Rule::in('square','rounded')]
                ]);

                $link->status = $fields['status'];
                $link->title = $fields['title'];
                $link->href = $fields['href'];
                $link->op_bg_color = $fields['op_bg_color'];
                $link->op_text_color = $fields['op_text_color'];
                $link->op_border_type = $fields['op_border_type'];
                $link->save();

                return redirect('/admin/'.$page->slug.'/links');


            }
        }

        return redirect('/admin');
    }

    // delete link
    public function delLink($slug,$linkid) {

        $user = Auth::user();
        $page = Page::where('id_user',$user->id)
                    ->where('slug',$slug)
                    ->first();


        if($page) {
            $link = Link::where('id_page',$page->id)
                     ->where('id',$linkid)
                     ->first();

            if($link) {
                $link->delete();

                 // Corrigindo as posições
                $allLinks = Link::where('id_page',$link->id_page)
                    ->orderBy('order','ASC')
                    ->get();

                foreach ($allLinks as $linkKey => $linkItem) {
                    $linkItem->order = $linkKey;
                    $linkItem->save();
                }

                return redirect('/admin/'.$page->slug.'/links');
            }
        }

        return redirect('/admin');
    }

    // Screen Page Design
    public function pageDesign($slug) {
        return view('admin/page_design',[
            'menu' => 'design'
        ]);
    }

    // Screen Page Stats
    public function pageStats($slug) {
        return view('admin/page_stats',[
            'menu' => 'stats'
        ]);
    }

    // Update link order
    public function linkOrderUpdate($linkid,$pos) {
        $user = Auth::user();

        $link = Link::find($linkid);

        $myPages = [];
        $myPagesQuery = Page::where('id_user',$user->id)->get();

        foreach ($myPagesQuery as $pageItem) {
           $myPages[] = $pageItem->id;
        }

        if(in_array($link->id_page,$myPages)) {
            // Subiu item de posição na ordem
            if($link->order > $pos) {
                // joga os próximos itens para baixo
                $afterLinks = Link::where('id_page',$link->id_page)
                     ->where('order','>=',$pos)
                     ->get();

                foreach ($afterLinks as $afterLink) {
                    $afterLink->order++;
                    $afterLink->save();
                }
            }
            // Desceu o item de posição na ordem
            elseif($link->order < $pos){
                // Jogando os anteriores para cima
                $beforeLinks = Link::where('id_page',$link->id_page)
                    ->where('order', '<=',$pos)
                    ->get();

                foreach ($beforeLinks as $beforeLink) {
                    $beforeLink->order--;
                    $beforeLink->save();
                }
            }

            // Posicionando o item selecionado
            $link->order = $pos;
            $link->save();

            // Corrigindo as posições
            $allLinks = Link::where('id_page',$link->id_page)
                ->orderBy('order','ASC')
                ->get();

            foreach ($allLinks as $linkKey => $linkItem) {
                $linkItem->order = $linkKey;
                $linkItem->save();
            }


        }
        return [];
    }
}
