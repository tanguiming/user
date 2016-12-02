<?php
/**
 * 包、以及包内容下模块的获取 model
 * 不用来对数据库操作，纯 组织 获取 包相关的数据
 *  
 * 	@author		songmw<song_mingwei@cdv.com>
 * @date			2013-11-19
 * @version	1.0
 */
namespace Weitac\User\Http\Models;

class PackageModule {
	
	// 存放所有模块对应的中文描述
	public $module;
	
	/**
	 *  获取正在使用的扩展包
	 *  扩展包数据 在 /config/workbench 的 packages 数组中
	 *  
	 *  @return array 返回扩展包
	 */
	public function getPackage()
	{
		return Config::get('workbench.packages');
	}
	
	/**
	 *  获取单个包下面所有的模块 以及 权限
	 *  
	 *  @param string $package 包名
	 *  @return array  该包下面的所有模块
	 */
	public function getPackageModule($package)
	{
		return Config::get($package . '::aca');
	}
	
	/**
	 *  获取多个包下面所有的模块 以及 权限
	 *  
	 *  @param array $packages 包  // 格式 array('packageDesc'=>'packageName')
	 *  @return array  包名=>array(
	 *  											'模块' => array(
	 *  												'actionName' => 'actionDesc',
	 *  											)
	 *  										)
	 */
	public function getPackageModuleAca($packages)
	{
		$acas = array();
		foreach($packages as $packageDesc => $packageName)
		{
			$rs = $this->getPackageModule($packageName);
			
			if(empty($rs))
				continue;
			
			$moduleDesc = $this->getPackageModuleDesc($rs);
			unset($rs['module']);
			$this->module[$packageName] = $moduleDesc;
			$acas[$packageName] = $rs;
		}
		
		return $acas;
	}
	
	/**
	 *  获取包里面模块的描述
	 *  
	 *  @param array $package 包 里面的 权限数组
	 *  @return array 模块对应的中文描述
	 */
	public function getPackageModuleDesc($package)
	{
		$module = empty($package['module']) ? null : $package['module'] ;
		return $module;
	}
	
	/**
	 *  获取全部 模块的 描述
	 *  
	 *  @return array 返回所有模块对应的中文描述
	 */
	public function getAllPackageModuleDesc()
	{
		$packages = $this->getPackage();
		$desc = $rs = array();

		foreach($packages as $package)
		{
			$rss = $this->getPackageModule($package);
			
			if(empty($rss))
				continue;
			
			$desc[] = $this->getPackageModuleDesc($rss);
		}
		
		$desc = array_dot($desc);		
		foreach($desc as $key=>$val)
		{
			$key = substr($key, stripos($key, '.') + 1);
			$rs[$key] = $val;
		}
		
		return $rs;
	}
}