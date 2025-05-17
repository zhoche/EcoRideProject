import { Routes } from '@angular/router';
import { HomeComponent } from './home/home.component';
import { ConnexionModule } from './connexion/connexion.module';
import { ConnexionComponent } from './connexion/connexion.component';
import { InscriptionComponent } from './inscription/inscription.component';
import { InscriptionModule } from './inscription/inscription.module';

export const routes: Routes = [
    { path: 'home' , component: HomeComponent },
    { path: '' , redirectTo: '/home', pathMatch: 'full' },
    { path: 'connexion' , component: ConnexionComponent },
    { path: 'inscription' , component: InscriptionComponent },
]
