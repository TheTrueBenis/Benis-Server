<table>
<?php

        foreach ($this->characters as $char) {

            if (false) {
                echo '<tr class="inactive">';
            } else {
                echo '<tr class="active">';
            }
			echo '<td>'.$char->user_name.'</td>';
            echo '<td>'.$char->Character_name.'</td>';
            echo '<td class="avatar">';

           /* if (isset($user->user_avatar_link)) {
                echo '<img src="'.$user->user_avatar_link.'" />';
            }

            echo '</td>';
			*/
			echo '<td>'.$char->Race.'</td>';
            echo '<td>'.$char->Class.'</td>';
			echo '<td>'.$char->XP.'</td>';
			/*
            echo '<td>'.$user->user_email.'</td>';
            echo '<td>Active: '.$user->user_active.'</td>';
            echo '<td><a href="'.URL.'admin/showuserprofile/'.$user->user_id.'">Show user\'s profile</a></td>';
			*/
            echo "</tr>";
        }

        ?>
</table>