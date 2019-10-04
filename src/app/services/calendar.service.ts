import { HttpClient, HttpParams } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { MonthlyCalendar } from '../classes/monthly-calendar';
import { map, catchError } from 'rxjs/operators';
import { Observable } from 'rxjs';
import { CurrentYearMonth } from '../classes/current-year-month';
import { EnvService } from './env.service';
@Injectable({
  providedIn: 'root'
})
export class CalendarService {

  constructor(private http: HttpClient, private env: EnvService) { }
  getCurrentYearMonth(): Observable<CurrentYearMonth> {
    return this.http.get(this.env.apiUrl + 'getCurrentYearMonth.php').pipe(map((res: CurrentYearMonth) => res));
  }
  getMonthlyCalendar(year: number, month: number): Observable<MonthlyCalendar> {

    if ((year == null) || (month == null)) {
      return this.http.get(this.env.apiUrl + 'getMonthlyCalendar.php').pipe(map((res: MonthlyCalendar) => res));
    } else {
      const parameters = new HttpParams().set('year', year.toString()).set('month', month.toString());
      return this.http.get(this.env.apiUrl + 'getMonthlyCalendar.php', {params: parameters}).pipe(map((res: MonthlyCalendar) => res));
    }
    /*
    return this.http.get('backend/getMonthlyCalendar.php', {params})
    .pipe(map((res: MonthlyCalendar) =>{ console.log('service:' + JSON.stringify(res)); return res; })
    //, catchError(this.handleError)
    );
    */
  }
}
