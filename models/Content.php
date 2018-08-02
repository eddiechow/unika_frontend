
	<?php
		
		header('Content-Type: application/json');
			
		require 'global.php';
			
		use DataAccess\NoticeDA;
		use Data\Notice;
			
		if (isset($_GET['action'])) 
		{
			$noticeDA = new NoticeDA();
			
			if ($_GET['action'] == "content")
			{	
				$custID = "C5160955";
					
				$notice = $noticeDA->getAllNotice($custID, Database::getConnection());

				for($i = 0; $i < count($notice); $i++)
				{
					$getnotice = array('chinese_notice' => $notice[$i]->getContentZhHant(), 'english_notice'=>$notice[$i]->getContentEnUs());
						
					echo json_encode($getnotice);
				}
			}
		}
	?>
		