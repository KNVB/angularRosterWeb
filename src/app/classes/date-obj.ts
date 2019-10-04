export class DateObj {
  public dayOfWeek: number;
  public festivalInfo: string;
  public isPublicHoliday = false;
  public isLeap = false; // 閏月
  public lunarDate: number;
  public lunarMonth: number;
  public lunarYear: number;
  public solarDate: number;
  public solarMonth: number;
  public solarYear: number;

  constructor() {}
}
