<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers;

use App\Category;
use App\PersonalFreeleech;

class CategoryController extends Controller
{
    /**
     * Show Categories
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function categories()
    {
        $categories = Category::all()->sortBy('position');

        return view('category.categories', ['categories' => $categories]);
    }

    /**
     * Show All Torrents Within A Category
     *
     * @param $slug
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function category($slug, $id)
    {
        $user = auth()->user();
        $category = Category::findOrFail($id);
        $torrents = $category->torrents()->latest()->paginate(25);
        $personal_freeleech = PersonalFreeleech::where('user_id', '=', $user->id)->first();


        return view('category.category', [
            'torrents' => $torrents,
            'user' => $user,
            'category' => $category,
            'personal_freeleech' => $personal_freeleech
        ]);
    }
}
