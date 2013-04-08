<!DOCTYPE html>
<html lang="sv">
<head>
	<meta charset="UTF-8" />
	<title>Formulär och populär hos Johan</title>
	<link href="//fonts.googleapis.com/css?family=Noto+Sans|EB+Garamond" rel="stylesheet" />
	<link href="style.css" rel="stylesheet" />
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script> window.jQuery || document.write('<script src="jquery.1.9.1.min.js"><\/script>'); </script>
	<script src="script.js"></script>
</head>
<body>
	<h1>Formulär<br /><span>och populär hos Johan</span></h1>
	<form action="submit.php" method="post">
		<?php if(isset($response['message'])): ?><p class="message <?php echo $response['status']; ?>"><?php echo $response['message']; ?></p><?php endif; ?>
		<?php if( ! isset($response['status']) OR $response['status'] !== 'success'): ?>
		<p>
			<input 
				name="name" 
				type="text" 
				value="<?php if(isset($post['name'])) echo $post['name']; ?>" 
				placeholder="Namn" 
				maxlength="100" 
				minlength="2" 
				<?php if(isset($errors['name'])) echo 'class="error"'; ?>
				required="required" />
		</p>
		<p>
			<input
				name="email" 
				type="email" 
				value="<?php if(isset($post['email'])) echo $post['email']; ?>" 
				placeholder="Epost" 
				maxlength="100" 
				minlength="5" 
				<?php if(isset($errors['email'])) echo 'class="error"'; ?>
				required="required" />
		</p>
		<p>
			<input
				name="phone" 
				type="tel" 
				value="<?php if(isset($post['phone'])) echo $post['phone']; ?>" 
				placeholder="Telefon" 
				maxlength="100" />
		</p>
		<p>
			<textarea 
				name="message" 
				cols="30" 
				rows="5" 
				placeholder="Meddelande" 
				maxlength="500" 
				minlength="2"
				<?php if(isset($errors['message'])) echo 'class="error"'; ?> 
				required="required"
			><?php if(isset($post['message'])) echo $post['message']; ?></textarea>
		</p>
		<p>
			<button name="submit" type="submit">Skicka</button>
		</p>
		<?php endif; ?>
	</form>
</body>
</html>