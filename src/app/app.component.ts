import { Component } from '@angular/core';
import { RouterOutlet } from '@angular/router';
import { filter } from 'rxjs/operators';


@Component({
  selector: 'app-root',
  imports: [RouterOutlet],
  templateUrl: './app.component.html',
  styleUrl: './app.component.scss'
})
export class AppComponent {
  title = 'ecorideproject';
}
