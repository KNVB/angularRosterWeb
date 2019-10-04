import { Component } from '@angular/core';
import { RosterService } from './services/roster.service';
import { CurrentYearMonth } from './classes/current-year-month';
@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent {

  constructor(private rosterService: RosterService) {

  }
}
