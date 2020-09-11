<div class='container-fluid'>
        <form class='formConnexion' method='POST' action='../Connexion_ctrl.php'>
        <div class="row justify-content-center">
                    <div class="col-8">
                    <img class="img-fluid rounded mt-5 text-center" width='200em' src="../assets/img/<?=$photo?>" alt="<?=$photo?>">
                    </div>

                    <div class="col-8">
                <div class="form-group mt-3 text-white text-center">
                    <label class="control-label" for="email">Email</label>
                    <input class="form-control w-50 <?= $email ? 'is-invalid' : ''?>" value="<?=$email?>" id="email" name="email" type="text" placeholder="Dave3452">
                        <div class="invalid-feedback"><?=$errors['email'] ?? ""?>
                        </div>
                </div>
                </div>

                    <div class="col-8">
                <div class="form-group text-white text-center mb-5">
                    <label class="control-label" for="password">Mot de passe</label>
                    <input class="form-control w-50<?=$isSubmitted && isset($errors['password']) ? 'is-invalid' : ''?>" value="<?=$password?>" id="password" type="password" name="password" placeholder="mot de passe">
                        <div class="invalid-feedback"><?=$errors['password'] ?? ""?>
                        </div>
                </div>
                </div>
                        
                <div class="text-center mt-4"> <button type="submit" class="btn btn-danger " name='connexion'>Se connecter</button>
                </div>
                
                </div>
        </form>
        </div>
</div>

<?php
include 'footer.php';