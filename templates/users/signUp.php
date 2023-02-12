<?php include __DIR__ . '/../header.php'; ?>
<div class="container my-5">
      <div class="row">
        <div class="col-md-6 offset-md-3">
        	<?php if (!empty($error)): ?>
        		<div class="alert alert-danger" role="alert">
				  <strong>Error!</strong> <?=$error?>
				</div>
        	<?php endif ?>
        	

          <form action="/users/register" method="post">
            <div class="form-group">
              <label for="name">Name</label>
              <input
                type="text"
                class="form-control"
                id="name"
                aria-describedby="nameHelp"
                placeholder="Enter your name"
                name = "name"
                value="<?= $_POST['name'] ?? '' ?>"
              />
            </div>
            <div class="form-group">
              <label for="email">Email address</label>
              <input
                type="email"
                class="form-control"
                id="email"
                aria-describedby="emailHelp"
                placeholder="Enter your email"
                name = "email"
                value="<?= $_POST['email'] ?? '' ?>"
              />
              <small id="emailHelp" class="form-text text-muted"
                >We'll never share your email with anyone else.</small
              >
            </div>
            <div class="form-group">
              <label for="password">Password</label>
              <input
                type="password"
                class="form-control"
                id="password"
                placeholder="Enter your password"
                name = "password"
                value="<?= $_POST['password'] ?? '' ?>"
                
              />
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
          </form>
        </div>
      </div>
    </div>
<?php include __DIR__ . '/../footer.php'; ?>
