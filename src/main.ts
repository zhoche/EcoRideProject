import { bootstrapApplication } from '@angular/platform-browser';
import { provideRouter } from '@angular/router';
import { AppComponent } from './app/app.component';
import { routes } from './app/app.routes';
import { provideHttpClient, withInterceptors } from '@angular/common/http';
import { AuthInterceptor } from './app/auth.interceptor';


bootstrapApplication(AppComponent, {
  providers: [
    provideHttpClient(
      withInterceptors([AuthInterceptor])
    ),
    provideRouter(routes)
  ]
}).catch(err => console.error(err));
