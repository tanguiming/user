<?php
/*
	权限的展示
*/	
namespace Weitac\User\Http\Models;
use Illuminate\Database\Eloquent\Model;
class UserMenu extends Model
{
	public  $table = 'menu';    //所操作的表 栏目及子栏目
	public  $timestamps =  false;


}