// src/main.ts
import { bootstrapApplication }  from '@angular/platform-browser';
import { importProvidersFrom }   from '@angular/core';
import { provideRouter }         from '@angular/router';
import { HttpClientModule }      from '@angular/common/http';

import { AppComponent }          from './app/app.component';
import { routes }                from './app/app.routes';

bootstrapApplication(AppComponent, {
  providers: [
    importProvidersFrom(HttpClientModule),
    provideRouter(routes)
  ]
})
.catch(err => console.error(err));
