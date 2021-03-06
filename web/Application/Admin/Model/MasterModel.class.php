<?php
namespace Admin\Model;
use Think\Model;

class MasterModel extends Model{

	protected $_validate = array(
	array('uid', 'require', '标识不能为空', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
	array('tags', 'require', '标签不能为空', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
	array('description', 'require', '描述不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
	);
	protected $_auto = array(
	array('status', 1, self::MODEL_INSERT),
	array('create_time', NOW_TIME, self::MODEL_INSERT),
	array('update_time', NOW_TIME, self::MODEL_BOTH),
	);


	/**
	 * 更新分类信息
	 * @return boolean 更新状态
	 * @author Rocks
	 */
	public function update(){
		$data = $this->create();
		if(!$data){ //数据对象创建错误
			return false;
		}

		/* 添加或更新数据 */
		if(empty($data['id'])){
			$res = $this->add();
		}else{
			$res = $this->save();
		}
		$this->cleanCache(array($data['id']));
		action_log('update_master', 'master', $data['id'] ? $data['id'] : $res, UID);
		return $res;
	}


	/**
	 * 清除指定Game数据
	 *
	 */
	public function cleanCache($ids) {
		if (empty ( $ids )) {
			return false;
		}
		! is_array ( $ids ) && $ids = explode ( ',', $ids );
		foreach ( $ids as $id ) {
			static_cache ( 'master_info_' . $id, false );
			$keys =S('master_info_'.$id);
			foreach ($keys as $k){
				S($k,null);
			}
			S('master_info_'.$id,null);
		}

		return true;
	}

}

