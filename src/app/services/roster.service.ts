import { HttpClient, HttpParams } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { map } from 'rxjs/operators';
import { Observable } from 'rxjs';
import { EnvService } from './env.service';
import { RosterRule } from '../classes/roster-rule';
import { ITORoster } from '../classes/itoroster';
@Injectable({
  providedIn: 'root'
})
export class RosterService {

  constructor(private http: HttpClient, private env: EnvService) { }
  getRosterRule() {
    return this.http.get(this.env.apiUrl + 'getRosterRule.php').pipe(map((res: RosterRule) => res));
  }
  getRosterList(year: number, month: number): Observable<ITORoster[]> {
    if ((year == null) || (month == null)) {
      return this.http.get(this.env.apiUrl + 'getRosterList.php').pipe(map((res: ITORoster[]) => res));
    } else {
      const parameters = new HttpParams().set('year', year.toString()).set('month', month.toString());
      return this.http.get(this.env.apiUrl + 'getRosterList.php', {params: parameters}).pipe(map((res: ITORoster[]) => res));
    }
  }

}
