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
    const formData = new FormData();
    if ((year !== null) || (month !== null)) {
      formData.append('year', year.toString());
      formData.append('month', month.toString());
    }
    return this.http.post(this.env.apiUrl + 'getMonthlyCalendar.php', formData).pipe(map((res: MonthlyCalendar) => res));
  }
}
