<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UserPortfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Rules\FileTypeValidate;

class ProfileController extends Controller
{
    public function profile()
    {
        $pageTitle = "Profile Setting";
        $user = auth()->user();
        return view('Template::user.profile.setting', compact('pageTitle','user'));
    }

    public function submitProfile(Request $request)
    {
        $user = auth()->user();
        $imageRule =  $user->image ? 'nullable' : 'required';
        $request->validate([
            'firstname'   => 'required|string',
            'lastname'    => 'required|string',
            'address'     => 'nullable|string',
            'state'       => 'nullable|string',
            'zip'         => 'nullable|string',
            'city'        => 'nullable|string',
            'country'     => 'nullable|string',
            'tagline'     => 'required|string',
            'description' => 'nullable|string',
            'image'       => [$imageRule, 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])]
        ],[
            'firstname.required'=>'The first name field is required',
            'lastname.required'=>'The last name field is required'
        ]);

        if ($request->hasFile('image')) {
            try {
                $old = $user->image;
                $user->image = fileUploader($request->image, getFilePath('userProfile'), getFileSize('userProfile'), $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }

        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;

        $user->address = $request->address;
        $user->city = $request->city;
        $user->state = $request->state;
        $user->zip = $request->zip;
        $user->tagline = $request->tagline;
        $user->description = $request->description;

        $user->save();
        $notify[] = ['success', 'Basic information updated successfully'];
        return back()->withNotify($notify);
    }

    public function educations()
    {
        $pageTitle = "Educations";
        $user      = auth()->user();
        return view('Template::user.profile.education', compact('pageTitle', 'user'));
    }

    public function submitEducatins(Request $request)
    {
        $request->validate([
            'educations'             => ['nullable', 'array'],
            'educations.*.institute' => 'required|string',
            'educations.*.title'     => 'required|string',
            'educations.*.year'      => 'required|string',
        ], [
            'educations.*.institute' => "Education institute fields must not be empty",
            'educations.*.title'     => "Education title fields must not be empty",
            'educations.*.year'      => "Education year fields must not be empty",
        ]);

        $user = auth()->user();
        $user->educations        = $request->educations ?? [];
        $user->save();
        $notify[] = ['success', 'Education information updated successfully'];
        return back()->withNotify($notify);
    }

    public function portfolio()
    {
        $pageTitle  = "Portfolios";
        $user       = auth()->user();
        $portfolios = UserPortfolio::where('user_id', $user->id)->paginate(getPaginate());
        return view('Template::user.profile.portfolio', compact('pageTitle', 'portfolios'));
    }

    public function submitPortfolio(Request $request, $id = 0)
    {
        $imageRule =  $id ? 'nullable' : 'required';
        $request->validate([
            'title'       => 'required|string',
            'description' => 'required|string',
            'image'       => [$imageRule, 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])]
        ]);

        $user  = auth()->user();

        if ($id) {
            $portfolio     = UserPortfolio::where('user_id', $user->id)->findOrFail($id);
            $notification = 'Portfolio updated successfully';
        } else {
            $portfolio     = new UserPortfolio();
            $notification = 'Portfolio added successfully';
            $portfolio->user_id     = $user->id;
        }

        if ($request->hasFile('image')) {
            try {
                $old = $portfolio->image;
                $portfolio->image = fileUploader($request->image, getFilePath('userPortfolio'), getFileSize('userPortfolio'), $old, "355x215");
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }

        $portfolio->title       = $request->title;
        $portfolio->description = $request->description;
        $portfolio->save();
        $notify[] = ['success',  $notification];
        return back()->withNotify($notify);
    }

    public function portfolioDelete($id)
    {
        $user      = auth()->user();
        $portfolio = UserPortfolio::where('user_id', $user->id)->findOrFail($id);
        unlink(getFilePath('userPortfolio') . '/' . $portfolio->image);
        $portfolio->delete();
        $notify[] = ['success',  'Portfolio deleted successfully'];
        return back()->withNotify($notify);
    }

    public function changePassword()
    {
        $pageTitle = 'Change Password';
        return view('Template::user.password', compact('pageTitle'));
    }

    public function submitPassword(Request $request)
    {

        $passwordValidation = Password::min(6);
        if (gs('secure_password')) {
            $passwordValidation = $passwordValidation->mixedCase()->numbers()->symbols()->uncompromised();
        }

        $request->validate([
            'current_password' => 'required',
            'password' => ['required','confirmed',$passwordValidation]
        ]);

        $user = auth()->user();
        if (Hash::check($request->current_password, $user->password)) {
            $password = Hash::make($request->password);
            $user->password = $password;
            $user->save();
            $notify[] = ['success', 'Password changed successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'The password doesn\'t match!'];
            return back()->withNotify($notify);
        }
    }
}
