import { Component, Input, OnInit,  OnChanges, SimpleChange } from '@angular/core';
import { RosterService } from 'src/app/services/roster.service';
import { ITORoster } from 'src/app/classes/itoroster';
import { RosterRule } from 'src/app/classes/roster-rule';
import { MonthlyCalendar } from 'src/app/classes/monthly-calendar';
@Component({
  selector: '[app-roster-body]',
  templateUrl: './roster-body.component.html',
  styleUrls: ['./roster-body.component.css']
})
export class RosterBodyComponent implements OnInit,  OnChanges {
  @Input () monthlyCalendar: MonthlyCalendar;
  year = 0;
  month = 0;
  noOfWorkingDay = 0;
  rosterList: ITORoster[];
  rosterRule: RosterRule;
  constructor(private rosterService: RosterService) {
    this.rosterService.getRosterRule().subscribe((res: RosterRule) => {
      this.rosterRule = res;
    });
  }

  ngOnInit() {

  }
  ngOnChanges(changes: {[propKey: string]: SimpleChange}) {
    if ((this.monthlyCalendar != null)) {
      this.noOfWorkingDay = this.monthlyCalendar.noOfWorkingDay;
      this.getRosterList();
    }
  }
  getRosterList() {
    this.rosterService.getRosterList(this.monthlyCalendar.year, this.monthlyCalendar.month).subscribe ((res: ITORoster[]) => {
      this.rosterList = res;
    });
    return this.rosterList;
  }
}
