import { Component, OnInit, OnDestroy } from '@angular/core';
import { RosterService } from 'src/app/services/roster.service';
import { ITORoster } from 'src/app/classes/itoroster';
import { RosterRule } from 'src/app/classes/roster-rule';
import { MonthlyCalendar } from 'src/app/classes/monthly-calendar';
import { TransferObjectService } from 'src/app/services/transfer-object.service';

@Component({
  selector: '[app-roster-body]',
  templateUrl: './roster-body.component.html',
  styleUrls: ['./roster-body.component.css']
})
export class RosterBodyComponent implements OnInit, OnDestroy {
  year = 0;
  month = 0;
  noOfWorkingDay = 0;
  rosterList: ITORoster[];
  rosterRule: RosterRule;
  subscription;
  constructor(private rosterService: RosterService,
              private transferObjectService: TransferObjectService,
              private transferObjectService2: TransferObjectService) {
    this.rosterService.getRosterRule().subscribe((res: RosterRule) => {
      this.rosterRule = res;
    });
  }

  ngOnInit() {
    this.subscription = this.transferObjectService.accessObj().subscribe((res: MonthlyCalendar) => {
      this.noOfWorkingDay = res.noOfWorkingDay;
      this.year = res.year;
      this.month = res.month;
      this.getRosterList();

    });
  }

  /**
   * don’t forget to call unsubscribe method when a component destroys,
   * otherwise, observables are active and create memory leaks in your app.
   */
  ngOnDestroy() {
    this.subscription.unsubscribe();
  }
  getRosterList() {
    this.rosterService.getRosterList(this.year, this.month).subscribe ((result: ITORoster[]) => {
      this.rosterList = result;
    });
  }
}
