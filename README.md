Do at least ONE of the following tasks: refactor is mandatory. Write tests is optional, will be good bonus to see it. 
Please do not invest more than 2-4 hours on this.
Upload your results to a Github repo, for easier sharing and reviewing.

Thank you and good luck!



Code to refactor
=================
1) app/Http/Controllers/BookingController.php
2) app/Repository/BookingRepository.php

Code to write tests (optional)
=====================
3) App/Helpers/TeHelper.php method willExpireAt
4) App/Repository/UserRepository.php, method createOrUpdate


----------------------------

I saw that you guys are using a repository design pattern which made me happy. But when I deeply reviewed the code, I realized that it's not a good practice to write code. But it could be good practice for others but not for me.
My opinions are given below:
1. Variables should be declared with specific format, camelCase or snack_case.
2. Don't use any unnecessary variables. I have seen there are many unnecessary variables which don't have any uses.
3. Writing code is not like writing an essay. So, make code simple and human-readable.
4. Try to write a method within 12 lines. Because short methods are easy to handle.
5. Follow the DRY(Don't Repeat Yourself) method.
6. Try to write reusable code.
7. Try to avoid extra variables. Because declaring a variable takes a new memory location.
8. Try to follow SOLID principles as well.
9. Break a large line of methods into small methods.
10. Use Laravel Request Class for validation.

```Example: ```
```
class DistanceFeedRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'jobid' => 'required',
            'distance' => 'nullable|string',
            'time' => 'nullable|string',
            'session_time' => 'nullable|string',
            'flagged' => 'required|in:true,false',
            'manually_handled' => 'required|in:true,false',
            'by_admin' => 'required|in:true,false',
            'admincomment' => 'nullable|string',
        ];
    }
}
```
11. You can also follow other design patterns like the Builder Design Pattern that I always follow. I used it on BookingController. Have a look at it.

```For Example: ```
```
public function distanceFeed(DistanceFeedRequest $request)
    {
        if ($request->get('flagged') == 'true' && ! $request->filled('admincomment')) {
            return $this->distanceFeedService->returnResponse('Please, add comment');
        }

        return DB::transaction(function () use ($request) {
            return $this->distanceFeedService
                ->makeDistanceData($request)
                ->makeJobData($request)
                ->updateDistance($request->input('jobid'))
                ->updateJob($request->input('jobid'))
                ->returnResponse();
        });

    }
```
12. Try to use type hinting everywhere.
13. Somewhere I used PHP 8 functionality. Ex: Union type. So try to update the system with new technology.
14. 
```example: ```
```
public function failedStoreResponse(string $message, string | null $field_name = null): array
    {
        return [
            'status' => 'fail',
            'message' => $message,
            'field_name' => $field_name
        ];
    }
```
14. Laravel is very smart. Follow Laravel doc and try to implement it in the system.
15. Review every pull request before merge.



What I expect in your repo:

X. A readme with:   Your thoughts about the code. What makes it amazing code. Or what makes it ok code. Or what makes it terrible code. How would you have done it. Thoughts on formatting, structure, logic.. The more details that you can provide about the code (what's terrible about it or/and what is good about it) the easier for us to assess your coding style, mentality etc

And 

Y.  Refactor it if you feel it needs refactoring. The more love you put into it. The easier for us to asses your thoughts, code principles etc


IMPORTANT: Make two commits. First commit with original code. Second with your refactor so we can easily trace changes. 


NB: you do not need to set up the code on local and make the web app run. It will not run as its not a complete web app. This is purely to assess you thoughts about code, formatting, logic etc


===== So expected output is a GitHub link with either =====

1. Readme described above (point X above) + refactored code 
OR
2. Readme described above (point X above) + refactored core + a unit test of the code that we have sent

Thank you!


