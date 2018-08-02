
	<?php
		
		header('Content-Type: application/json');
			
		require 'global.php';
			
		use DataAccess\DiscountDA;
		use Data\Discount;
			
		if (isset($_GET['action'])) 
		{
			$discountDA = new DiscountDA();
			
			if ($_GET['action'] == "discount")
			{	
				$discount = $discountDA->getDiscounts(Database::getConnection());

				for($i = 0; $i < count($discount); $i++)
				{	
					$getdiscount = array('chinese_discount' => $discount[$i]->getDescriptionZhHant(), 'english_discount'=>$discount[$i]->getDescriptionEnUS());

					echo json_encode($getdiscount);
				}
			}
		}
	?>
		